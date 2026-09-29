<?php

declare(strict_types=1);

namespace Bpt\Auth;

use Bpt\Codec\TlCodec;
use Bpt\Crypto\AesIge;
use Bpt\Crypto\PqFactor;
use Bpt\Crypto\RsaKey;
use Bpt\Crypto\RsaPad;
use Bpt\Transport\AbridgedTransport;
use Bpt\Transport\MsgIdGenerator;

/**
 * Full DH handshake from https://core.telegram.org/mtproto/samples-auth_key.
 *
 * Steps: req_pq_multi → resPQ → factor pq → req_DH_params (RSA_PAD) →
 * server_DH_params_ok (IGE) → set_client_DH_params → dh_gen_ok.
 *
 * The transport must already be connected (0xEF sent).
 */
final class AuthKeyExchange
{
    /**
     * @param AbridgedTransport $t        Connected transport.
     * @param array<string,RsaKey> $keys  Fingerprint-hex => key.
     * @param int $dcInner DC id for p_q_inner_data (add 10000 on test DCs).
     * @param MsgIdGenerator $msgIds Plain-handshake msg_id source.
     * @return AuthKeyResult
     * @throws \RuntimeException On any protocol violation.
     */
    public function run(AbridgedTransport $t, array $keys, int $dcInner, MsgIdGenerator $msgIds): AuthKeyResult
    {
        // 1) req_pq_multi#be7e8ef1
        $nonce = random_bytes(16);
        $this->plainSend($t, $msgIds, TlCodec::packInt(0xBE7E8EF1) . $nonce);

        // 2) resPQ#05162463
        $body = $this->plainRecv($t);
        $off = 0;
        $this->expect($body, $off, 0x05162463, 'resPQ');
        if (substr($body, $off, 16) !== $nonce) {
            throw new \RuntimeException('nonce mismatch');
        }
        $off += 16;
        $serverNonce = substr($body, $off, 16);
        $off += 16;
        $pqBin = TlCodec::decodeBytes($body, $off);
        $off += 4; // Vector id 0x1cb5c415
        $cnt = TlCodec::unpackInt($body, $off);
        $off += 4;
        $fps = [];
        for ($i = 0; $i < $cnt; $i++) {
            $fps[] = strtolower(bin2hex(substr($body, $off, 8)));
            $off += 8;
        }
        $use = null;
        foreach ($fps as $f) {
            if (isset($keys[$f])) {
                $use = $keys[$f];
                break;
            }
        }
        if ($use === null) {
            throw new \RuntimeException('no shared RSA fingerprint');
        }

        // 3) factor pq
        [$pG, $qG] = PqFactor::factor(gmp_import($pqBin));
        $pBin = gmp_export($pG);
        $qBin = gmp_export($qG);

        // 4+5) p_q_inner_data_dc#a9f55f95 + req_DH_params#d712e4be
        $newNonce = random_bytes(32);
        $inner = TlCodec::packInt(0xA9F55F95)
            . TlCodec::encodeBytes($pqBin) . TlCodec::encodeBytes($pBin)
            . TlCodec::encodeBytes($qBin) . $nonce . $serverNonce . $newNonce
            . TlCodec::packInt($dcInner);
        if (strlen($inner) > 144) {
            throw new \RuntimeException('p_q_inner_data too large for RSA_PAD');
        }
        $enc = RsaPad::encrypt($inner, $use);
        $this->plainSend(
            $t,
            $msgIds,
            TlCodec::packInt(0xD712E4BE) . $nonce . $serverNonce
            . TlCodec::encodeBytes($pBin) . TlCodec::encodeBytes($qBin)
            . $use->fp . TlCodec::encodeBytes($enc)
        );

        // 6) server_DH_params_ok#d0e8075c
        $body = $this->plainRecv($t);
        $off = 0;
        $this->expect($body, $off, 0xD0E8075C, 'server_DH_params_ok');
        $off += 32;
        $encAns = TlCodec::decodeBytes($body, $off);
        [$tk, $iv] = self::tempKeys($newNonce, $serverNonce);
        $answer = self::igeDecryptWithHash($encAns, $tk, $iv);
        $off = 0;
        $this->expect($answer, $off, 0xB5890DBA, 'server_DH_inner_data');
        $off += 32;
        $g = TlCodec::unpackInt($answer, $off);
        $off += 4;
        $dhPrime = TlCodec::decodeBytes($answer, $off);
        $gA = TlCodec::decodeBytes($answer, $off);
        $serverTime = TlCodec::unpackInt($answer, $off);
        $P = gmp_import($dhPrime);
        $GA = gmp_import($gA);
        if (gmp_cmp($GA, 1) <= 0 || gmp_cmp($GA, gmp_sub($P, 1)) >= 0) {
            throw new \RuntimeException('invalid g_a');
        }

        // 7) set_client_DH_params#f5045f1f
        $bBin = random_bytes(256);
        $bBin[0] = chr(ord($bBin[0]) | 0x80);
        $B = gmp_import($bBin);
        $authKey = str_pad(gmp_export(gmp_powm($GA, $B, $P)), 256, "\0", STR_PAD_LEFT);
        $gBBin = str_pad(gmp_export(gmp_powm(gmp_init($g), $B, $P)), 256, "\0", STR_PAD_LEFT);
        $cliInner = TlCodec::packInt(0x6643B654) . $nonce . $serverNonce . str_repeat("\0", 8) . TlCodec::encodeBytes($gBBin);
        $dh = sha1($cliInner, true) . $cliInner;
        $dh .= random_bytes((16 - strlen($dh) % 16) % 16);
        $this->plainSend(
            $t,
            $msgIds,
            TlCodec::packInt(0xF5045F1F) . $nonce . $serverNonce . TlCodec::encodeBytes(AesIge::encrypt($dh, $tk, $iv))
        );

        // 8-9) dh_gen_ok#3bcbf734
        $body = $this->plainRecv($t);
        $off = 0;
        $this->expect($body, $off, 0x3BCBF734, 'dh_gen_ok');
        $off += 32;
        $nh = substr($body, $off, 16);
        $aux = substr(sha1($authKey, true), 0, 8);
        if (!hash_equals($nh, substr(sha1($newNonce . chr(1) . $aux, true), -16))) {
            throw new \RuntimeException('new_nonce_hash mismatch');
        }

        $salt = substr($newNonce, 0, 8) ^ substr($serverNonce, 0, 8);
        return new AuthKeyResult($authKey, $salt, $serverTime - time(), substr(sha1($authKey, true), 12, 8));
    }

    /**
     * Derive temporary AES key/IV for the DH answer (auth_key §6).
     *
     * @return array{0:string,1:string} [key(32), iv(32)]
     */
    public static function tempKeys(string $newNonce, string $serverNonce): array
    {
        $k = sha1($newNonce . $serverNonce, true) . substr(sha1($serverNonce . $newNonce, true), 0, 12);
        $iv = substr(sha1($serverNonce . $newNonce, true), 12, 8)
            . sha1($newNonce . $newNonce, true) . substr($newNonce, 0, 4);
        return [$k, $iv];
    }

    /**
     * IGE-decrypt and strip SHA1+padding (answer_with_hash).
     *
     * @throws \RuntimeException If the SHA1 check fails.
     */
    private static function igeDecryptWithHash(string $enc, string $key, string $iv): string
    {
        $dec = AesIge::decrypt($enc, $key, $iv);
        $h = substr($dec, 0, 20);
        for ($l = 0; $l < 16; $l++) {
            $c = substr($dec, 20, strlen($dec) - 20 - $l);
            if (sha1($c, true) === $h) {
                return $c;
            }
        }
        throw new \RuntimeException('bad answer hash');
    }

    /**
     * @throws \RuntimeException
     */
    private function expect(string $body, int &$off, int $ctor, string $name): void
    {
        $got = TlCodec::unpackInt($body, $off);
        $off += 4;
        if ($got !== $ctor) {
            throw new \RuntimeException("$name expected, got 0x" . dechex($got));
        }
    }

    /**
     * Send an unencrypted (auth_key_id = 0) message.
     */
    private function plainSend(AbridgedTransport $t, MsgIdGenerator $m, string $body): void
    {
        $t->send(str_repeat("\0", 8) . TlCodec::packLong($m->next()) . TlCodec::packInt(strlen($body)) . $body);
    }

    /**
     * Receive an unencrypted message body (strips 20-byte header).
     */
    private function plainRecv(AbridgedTransport $t): string
    {
        return substr($t->receive(), 20);
    }
}
