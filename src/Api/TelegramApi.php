<?php

declare(strict_types=1);

namespace Bpt\Api;

use Bpt\Auth\PasswordInfo;
use Bpt\Codec\TlCodec;
use Bpt\Crypto\Srp;
use Bpt\Session\EncryptedSession;
use Bpt\Session\RpcErrorException;

/**
 * High-level Telegram API over an EncryptedSession.
 *
 * BC hub: old auth methods stay here; new handwritten namespaces live in
 * UsersApi/ContactsApi/MessagesApi/ChannelsApi/MetaApi and are exposed
 * through users()/contacts()/messages()/channels()/meta().
 */
final class TelegramApi
{
    private ?UsersApi $usersApi = null;
    private ?ContactsApi $contactsApi = null;
    private ?MessagesApi $messagesApi = null;
    private ?ChannelsApi $channelsApi = null;
    private ?MetaApi $metaApi = null;
    private ?UploadApi $uploadApi = null;

    /**
     * @param EncryptedSession $session Live encrypted session.
     * @param int $apiId  Application id from my.telegram.org.
     * @param int $layer  API layer (e.g. 204).
     */
    public function __construct(
        private readonly EncryptedSession $session,
        private readonly int $apiId,
        private readonly int $layer,
    ) {
    }

    public function users(): UsersApi
    {
        return $this->usersApi ??= new UsersApi($this->session, $this->apiId, $this->layer);
    }

    public function contacts(): ContactsApi
    {
        return $this->contactsApi ??= new ContactsApi($this->session, $this->apiId, $this->layer);
    }

    public function messages(): MessagesApi
    {
        return $this->messagesApi ??= new MessagesApi($this->session, $this->apiId, $this->layer);
    }

    public function channels(): ChannelsApi
    {
        return $this->channelsApi ??= new ChannelsApi($this->session, $this->apiId, $this->layer);
    }

    public function meta(): MetaApi
    {
        return $this->metaApi ??= new MetaApi($this->session, $this->apiId, $this->layer);
    }

    public function upload(): UploadApi
    {
        return $this->uploadApi ??= new UploadApi($this->session, $this->apiId, $this->layer);
    }

    /** Generic raw call: $body is a complete TL method (ctor + params). */
    public function call(string $methodBody): string
    {
        return $this->session->callWithLayer($methodBody, $this->apiId, $this->layer);
    }

    /** Drain side-pushed Updates queued during call(). Raw TL blobs. */
    public function drainPendingUpdates(): array
    {
        return $this->session->drainPendingUpdates();
    }

    /**
     * help.getNearestDc#1fb33026 (no auth needed, proves the channel works).
     *
     * @return array{country:string,nearest:int,this_dc:int}
     */
    public function getNearestDc(): array
    {
        $r = $this->session->callWithLayer(TlCodec::packInt(0x1FB33026), $this->apiId, $this->layer);
        $off = 0;
        $ctor = TlCodec::unpackInt($r, $off);
        $off += 4;
        if ($ctor !== 0x8E1A1775) {
            throw new \RuntimeException('expected nearestDc, got 0x' . dechex($ctor));
        }
        $country = TlCodec::decodeBytes($r, $off);
        $nearest = TlCodec::unpackInt($r, $off);
        $off += 4;
        return ['country' => $country, 'nearest' => $nearest, 'this_dc' => TlCodec::unpackInt($r, $off)];
    }

    /**
     * auth.sendCode#a677244f.
     *
     * @param string $phone   International format, e.g. +989120000000.
     * @param string $apiHash App hash.
     * @return string phone_code_hash (to be stored for signIn).
     * @throws RpcErrorException e.g. PHONE_MIGRATE_X.
     */
    public function sendCode(string $phone, string $apiHash): string
    {
        $body = TlCodec::packInt(0xA677244F) . TlCodec::encodeBytes($phone)
            . TlCodec::packInt($this->apiId) . TlCodec::encodeBytes($apiHash)
            . TlCodec::packInt(0xAD253D78) . TlCodec::packInt(0);
        $r = $this->session->callWithLayer($body, $this->apiId, $this->layer);
        return self::parseSentCode($r);
    }

    /**
     * Parse auth.sentCode#5e002502 → phone_code_hash.
     *
     * Handles sentCodeTypeApp#3dbb5986 length:int.
     */
    public static function parseSentCode(string $b): string
    {
        $off = 0;
        $ctor = TlCodec::unpackInt($b, $off);
        $off += 4;
        if ($ctor !== 0x5E002502) {
            throw new \RuntimeException('expected sentCode, got 0x' . dechex($ctor));
        }
        $off += 4; // flags
        $tctor = TlCodec::unpackInt($b, $off);
        $off += 4;
        if ($tctor === 0x3DBB5986) {
            $off += 4; // length:int (e.g. 5)
        } else {
            throw new \RuntimeException('unsupported sentCode type 0x' . dechex($tctor));
        }
        return TlCodec::decodeBytes($b, $off);
    }

    /**
     * auth.signIn#8d52a951.
     *
     * @return string Raw auth.authorization object.
     * @throws RpcErrorException e.g. PHONE_CODE_INVALID, SESSION_PASSWORD_NEEDED.
     */
    public function signIn(string $phone, string $phoneHash, string $code): string
    {
        $body = TlCodec::packInt(0x8D52A951) . TlCodec::packInt(1)
            . TlCodec::encodeBytes($phone) . TlCodec::encodeBytes($phoneHash) . TlCodec::encodeBytes($code);
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /**
     * auth.exportAuthorization#e5bfffcd — copy login to another DC without SMS.
     *
     * Call while still connected to the AUTHORIZED (source) DC, then pass
     * the result to importAuthorization() after switching DCs.
     *
     * @return array{id:int,bytes:string} User id + opaque auth blob.
     */
    public function exportAuthorization(int $targetDcId): array
    {
        $body = TlCodec::packInt(0xE5BFFFCD) . TlCodec::packInt($targetDcId);
        $r = $this->session->callWithLayer($body, $this->apiId, $this->layer);
        $off = 0;
        $ctor = TlCodec::unpackInt($r, $off);
        $off += 4;
        if ($ctor === 0xB434E2B8) { // auth.exportedAuthorization (current layers)
            $lo = TlCodec::unpackInt($r, $off);
            $hi = TlCodec::unpackInt($r, $off + 4);
            $off += 8;
            $v = gmp_add(gmp_mul($hi, 4294967296), $lo);
            if (gmp_cmp($v, '9223372036854775807') > 0) {
                $v = gmp_sub($v, '18446744073709551616');
            }
            return ['id' => gmp_intval($v), 'bytes' => TlCodec::decodeBytes($r, $off)];
        }
        if ($ctor === 0xDF969C2D) { // legacy shape (int id)
            $id = TlCodec::unpackInt($r, $off);
            $off += 4;
            return ['id' => $id, 'bytes' => TlCodec::decodeBytes($r, $off)];
        }
        throw new \RuntimeException('exportAuthorization: unexpected 0x' . dechex($ctor));
    }

    /**
     * auth.importAuthorization#a57a7dad — activate an exported login on this DC.
     *
     * @return string Raw auth.authorization object.
     */
    public function importAuthorization(int $userId, string $bytes): string
    {
        $body = TlCodec::packInt(0xA57A7DAD) . TlCodec::packLong($userId) . TlCodec::encodeBytes($bytes);
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /**
     * messages.sendMessage#545cd15a to Saved Messages (inputPeerSelf#7da07ec9).
     * Kept for BC; new code should use messages()->sendMessageToSelf().
     *
     * @param string $message UTF-8 text (1..4096 chars).
     * @return string Raw Updates object.
     * @throws RpcErrorException e.g. AUTH_KEY_UNREGISTERED, MESSAGE_EMPTY, FLOOD_WAIT_x.
     */
    public function sendMessageToSelf(string $message): string
    {
        return $this->messages()->sendMessageToSelf($message);
    }

    /**
     * Full 2FA: account.getPassword#548a30f5 + auth.checkPassword#d18b4d16.
     *
     * @return string Raw auth.authorization object.
     */
    public function checkPassword(string $password): string
    {
        $pwd = $this->session->callWithLayer(TlCodec::packInt(0x548A30F5), $this->apiId, $this->layer);
        $info = PasswordInfo::parse($pwd);
        [$A, $M1] = Srp::compute($password, $info->salt1, $info->salt2, $info->g, $info->p, $info->srpB);
        $body = TlCodec::packInt(0xD18B4D16) . TlCodec::packInt(0xD27FF082)
            . $info->srpIdRaw . TlCodec::encodeBytes($A) . TlCodec::encodeBytes($M1);
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }
}
