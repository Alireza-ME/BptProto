<?php

declare(strict_types=1);

namespace Bpt\Session;

use Bpt\Codec\TlCodec;

/**
 * Helpers for peeling MTProto containers and rpc_results.
 */
final class TlResponse
{
    /**
     * Decompress gzip_packed#3072cfa1 wrappers (recursively).
     *
     * Servers gzip large results (e.g. Updates); without this the caller
     * sees ctor 3072cfa1 instead of the real object.
     */
    public static function ungzip(string $data): string
    {
        for ($i = 0; $i < 8; $i++) {
            if (strlen($data) < 4 || TlCodec::unpackInt($data, 0) !== 0x3072CFA1) {
                return $data;
            }
            $off = 4;
            $packed = TlCodec::decodeBytes($data, $off);
            $inner = @gzdecode($packed);
            if ($inner === false) {
                throw new \RuntimeException('bad gzip_packed payload');
            }
            $data = $inner;
        }
        return $data;
    }

    /**
     * Recursively search for an rpc_result (or bare result) object.
     *
     * Skips new_session_created, msgs_ack, bad_msg_notification, etc.
     * by returning null so the caller reads the next packet.
     *
     * @return string|null Full rpc_result object (including its header).
     */
    public static function findRpcResult(string $res): ?string
    {
        $res = self::ungzip($res);
        if (strlen($res) < 4) {
            return null;
        }
        $ctor = TlCodec::unpackInt($res, 0);
        if ($ctor === 0xF35C6D01) {
            return $res;
        }
        if ($ctor === 0x73F1F8DC) {
            $off = 8;
            $cnt = TlCodec::unpackInt($res, 4);
            for ($i = 0; $i < $cnt; $i++) {
                if ($off + 16 > strlen($res)) {
                    break;
                }
                $off += 12;
                $len = TlCodec::unpackInt($res, $off);
                $off += 4;
                $body = substr($res, $off, $len);
                $off += $len;
                $r = self::findRpcResult($body);
                if ($r !== null) {
                    return $r;
                }
            }
        }
        return null;
    }

    /**
     * Unwrap rpc_result → inner object, throwing on rpc_error.
     *
     * @param string $rpcResult Full rpc_result object.
     * @return string Inner result object (constructor + fields).
     * @throws RpcErrorException
     */
    public static function unwrapRpcResult(string $rpcResult): string
    {
        $inner = substr($rpcResult, 12); // req_msg_id
        $ctor = TlCodec::unpackInt($inner, 0);
        if ($ctor === 0x2144CA19) {
            $off = 4;
            $code = TlCodec::unpackInt($inner, $off);
            $off += 4;
            throw new RpcErrorException($code, TlCodec::decodeBytes($inner, $off));
        }
        return self::ungzip($inner);
    }

    /**
     * Locate a new_session_created#9ec20908 object inside a received packet
     * (top-level or msg_container). Sent by the server when it has created a
     * fresh session for a reconnecting client; carries the new session id and
     * the current server salt.
     *
     * @return array{unique_id:string,server_salt:string}|null 8-byte values.
     */
    public static function findNewSessionCreated(string $res): ?array
    {
        $obj = self::findCtor($res, [0x9EC20908]);
        if ($obj === null || strlen($obj) < 28) {
            return null;
        }
        // ctor(4) + first_msg_id long(8) + unique_id long(8) + server_salt long(8)
        return [
            'unique_id' => substr($obj, 12, 8),
            'server_salt' => substr($obj, 20, 8),
        ];
    }

    /**
     * Locate a bad_server_salt#edab447b object and return its new salt.
     *
     * Sent when the client used a stale salt; the caller must update the salt
     * and resend the query.
     *
     * @return string|null 8-byte new_server_salt.
     */
    public static function findBadServerSalt(string $res): ?string
    {
        $obj = self::findCtor($res, [0xEDAB447B]);
        if ($obj === null || strlen($obj) < 28) {
            return null;
        }
        // ctor(4) + bad_msg_id long(8) + bad_msg_seqno int(4) + error_code int(4) + new_server_salt long(8)
        return substr($obj, 20, 8);
    }

    /**
     * Recursively search for any of the given constructors.
     *
     * @param int[] $want Little-endian ctor ids.
     * @return string|null Matching object.
     */
    public static function findCtor(string $res, array $want): ?string
    {
        if (strlen($res) < 4) {
            return null;
        }
        $ctor = TlCodec::unpackInt($res, 0);
        if (in_array($ctor, $want, true)) {
            return $res;
        }
        if ($ctor === 0x73F1F8DC) {
            $off = 8;
            $cnt = TlCodec::unpackInt($res, 4);
            for ($i = 0; $i < $cnt; $i++) {
                if ($off + 16 > strlen($res)) {
                    break;
                }
                $off += 12;
                $len = TlCodec::unpackInt($res, $off);
                $off += 4;
                $r = self::findCtor(substr($res, $off, $len), $want);
                $off += $len;
                if ($r !== null) {
                    return $r;
                }
            }
        }
        return null;
    }

    /**
     * Collect side-pushed Updates siblings from a received packet.
     *
     * call() only needs the rpc_result, but the same msg_container often
     * carries realtime events (updateShortMessage, updates, ...). Those
     * were silently dropped before; now they are returned as raw blobs
     * so EncryptedSession can queue them for pollUpdates().
     *
     * @return string[] Raw Updates objects (ungzip'd, top-level).
     */
    public static function collectSideUpdates(string $res): array
    {
        $res = self::ungzip($res);
        if (strlen($res) < 4) {
            return [];
        }
        $ctor = TlCodec::unpackInt($res, 0);
        if ($ctor === 0xF35C6D01) {
            return []; // pure rpc_result
        }
        if ($ctor === 0x73F1F8DC) {
            $out = [];
            $off = 8;
            try {
                $cnt = TlCodec::unpackInt($res, 4);
            } catch (\Throwable) {
                return [];
            }
            for ($i = 0; $i < $cnt; $i++) {
                if ($off + 16 > strlen($res)) {
                    break;
                }
                $off += 12; // msg_id + seq + len prefix handled below
                if ($off + 4 > strlen($res)) {
                    break;
                }
                $len = TlCodec::unpackInt($res, $off);
                $off += 4;
                $body = substr($res, $off, $len);
                $off += $len;
                // Recursion already returns the update blob for Updates-kind
                // bodies and [] for rpc_result/service messages. No extra
                // handling here (it would double-count).
                foreach (self::collectSideUpdates($body) as $u) {
                    $out[] = $u;
                }
            }
            return $out;
        }
        if (\Bpt\Entity\Updates::isUpdatesCtor($ctor)) {
            return [$res];
        }
        return [];
    }
}
