<?php

declare(strict_types=1);

namespace Bpt\Session;

use Bpt\Codec\TlCodec;
use Bpt\Crypto\AesIge;
use Bpt\Transport\AbridgedTransport;
use Bpt\Transport\MsgIdGenerator;

/**
 * MTProto 2.0 encrypted session (one TCP connection, one auth_key).
 *
 * Frame: auth_key_id(8) + msg_key(16) + AES-IGE(plaintext_padded)
 * KDF:   msg_key_large = SHA256(auth_key[88:120] + plaintext_padded) [client→server]
 *
 * @see https://core.telegram.org/mtproto/description
 */
final class EncryptedSession
{
    private string $sessionId;
    private int $seq = 0;
    /** @var string[] Raw Updates blobs seen alongside RPC replies. */
    private array $pendingUpdates = [];

    /**
     * @param AbridgedTransport $transport Connected transport.
     * @param string $authKey 256-byte authorization key.
     * @param string $salt    8-byte server salt.
     * @param MsgIdGenerator $msgIds Clock-synced msg_id source (server time offset applied).
     * @param string|null $sessionId Resume a persisted session (null = new random one).
     * @param int $seqStart Resume persisted content-message counter (0 = fresh).
     * @param callable|null $onStateChange Invoked when the salt/session id change
     *        (new_session_created / bad_server_salt) so the owner can persist them.
     */
    public function __construct(
        private readonly AbridgedTransport $transport,
        private readonly string $authKey,
        private string $salt,
        private readonly MsgIdGenerator $msgIds,
        ?string $sessionId = null,
        int $seqStart = 0,
        private $onStateChange = null,
    ) {
        $this->sessionId = $sessionId ?? random_bytes(8);
        $this->seq = $seqStart;
    }

    /** Live session id (persist to resume the session across processes). */
    public function sessionId(): string
    {
        return $this->sessionId;
    }

    /** Current server salt (persist alongside the session id). */
    public function salt(): string
    {
        return $this->salt;
    }

    /** Content-message counter (persist alongside the session id). */
    public function seq(): int
    {
        return $this->seq;
    }

    /** Last issued msg_id (persist for monotonicity). */
    public function lastMsgId(): int
    {
        return $this->msgIds->getLast();
    }

    /**
     * Send a raw TL query body and return the inner rpc result.
     *
     * Skips service messages (msgs_ack, new_session_created) AND stale
     * rpc_results left over from resumed sessions (matched by req_msg_id),
     * reading follow-up packets until OUR result arrives.
     *
     * Also keeps the session alive across salt/session rotations: a
     * new_session_created updates the session id + salt, and a
     * bad_server_salt updates the salt and re-sends the query — both are
     * reported through the onStateChange callback so the owner persists them.
     *
     * Side-pushed Updates found in the same containers are NOT dropped:
     * they are queued and can be drained with drainPendingUpdates().
     *
     * @param string $queryBody TL-serialized query (constructor + params).
     * @return string Inner result object (after rpc_result header).
     * @throws RpcErrorException If the server returns rpc_error.
     * @throws \RuntimeException On transport/crypto failure.
     */
    public function call(string $queryBody): string
    {
        $reqId = $this->send($queryBody);
        for ($i = 0; $i < 12; $i++) {
            $res = $this->receive();

            $nsc = TlResponse::findNewSessionCreated($res);
            if ($nsc !== null) {
                $this->sessionId = $nsc['unique_id'];
                $this->salt = $nsc['server_salt'];
                $this->notifyStateChange();
            }

            $newSalt = TlResponse::findBadServerSalt($res);
            if ($newSalt !== null) {
                $this->salt = $newSalt;
                $this->notifyStateChange();
                $reqId = $this->send($queryBody);
                continue;
            }

            foreach (TlResponse::collectSideUpdates($res) as $u) {
                $this->pendingUpdates[] = $u;
            }
            $inner = TlResponse::findRpcResult($res);
            if ($inner === null) {
                continue;
            }
            if (self::rpcReqId($inner) !== $reqId) {
                continue; // somebody else's (stale) result: keep reading
            }
            return TlResponse::unwrapRpcResult($inner);
        }
        throw new \RuntimeException('no rpc_result in 12 packets');
    }

    /**
     * Drain queued side-pushed Updates (raw TL blobs, oldest first).
     *
     * @return string[]
     */
    public function drainPendingUpdates(): array
    {
        $out = $this->pendingUpdates;
        $this->pendingUpdates = [];
        return $out;
    }

    /**
     * Notify the owner that the salt / session id changed (persist them).
     */
    private function notifyStateChange(): void
    {
        if ($this->onStateChange !== null) {
            ($this->onStateChange)();
        }
    }

    /**
     * req_msg_id carried by an rpc_result object (bytes 4..12).
     */
    public static function rpcReqId(string $rpcResult): int
    {
        return \Bpt\TL\Reader::of(substr($rpcResult, 4, 8))->long();
    }

    /**
     * Wrap a method in invokeWithLayer + initConnection and call it.
     *
     * @param string $methodBody TL method (e.g. help.getNearestDc).
     * @param int    $apiId
     * @param int    $layer
     * @return string Inner result object.
     */
    public function callWithLayer(string $methodBody, int $apiId, int $layer): string
    {
        $init = TlCodec::packInt(0xC1CD5EA9) . TlCodec::packInt(0) . TlCodec::packInt($apiId)
            . TlCodec::encodeBytes('pc') . TlCodec::encodeBytes('1.0') . TlCodec::encodeBytes('1.0')
            . TlCodec::encodeBytes('en') . TlCodec::encodeBytes('') . TlCodec::encodeBytes('en') . $methodBody;
        return $this->call(TlCodec::packInt(0xDA9B0D0D) . TlCodec::packInt($layer) . $init);
    }

    /**
     * Encrypt and send one message.
     *
     * @return int The msg_id used (to match the rpc_result against).
     */
    private function send(string $body): int
    {
        $mid = $this->msgIds->next();
        $sq = $this->seq * 2 + 1;
        $this->seq++;
        $pl = $this->salt . $this->sessionId . TlCodec::packLong($mid) . TlCodec::packInt($sq) . TlCodec::packInt(strlen($body)) . $body;
        $pad = (16 - strlen($pl) % 16) % 16;
        if ($pad < 12) {
            $pad += 16;
        }
        $pl .= random_bytes($pad);
        $mk = substr(hash('sha256', substr($this->authKey, 88, 32) . $pl, true), 8, 16);
        [$ak, $aiv] = self::kdf($mk, $this->authKey, false);
        $this->transport->send(substr(sha1($this->authKey, true), 12, 8) . $mk . AesIge::encrypt($pl, $ak, $aiv));
        return $mid;
    }

    /**
     * Receive and decrypt one message body.
     */
    private function receive(): string
    {
        $p = $this->transport->receive();
        $mk = substr($p, 8, 16);
        [$ak, $aiv] = self::kdf($mk, $this->authKey, true);
        $pl = AesIge::decrypt(substr($p, 24), $ak, $aiv);
        if (substr(hash('sha256', substr($this->authKey, 96, 32) . $pl, true), 8, 16) !== $mk) {
            throw new \RuntimeException('bad msg_key');
        }
        return substr($pl, 32, TlCodec::unpackInt($pl, 28));
    }

    /**
     * MTProto 2.0 KDF.
     *
     * @return array{0:string,1:string} [aes_key(32), aes_iv(32)]
     */
    public static function kdf(string $msgKey, string $authKey, bool $fromServer): array
    {
        $x = $fromServer ? 8 : 0;
        $a = hash('sha256', $msgKey . substr($authKey, $x, 36), true);
        $b = hash('sha256', substr($authKey, 40 + $x, 36) . $msgKey, true);
        return [
            substr($a, 0, 8) . substr($b, 8, 16) . substr($a, 24, 8),
            substr($b, 0, 8) . substr($a, 8, 16) . substr($b, 24, 8),
        ];
    }
}
