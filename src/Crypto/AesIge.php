<?php

declare(strict_types=1);

namespace Bpt\Crypto;

/**
 * AES-256-IGE encryption used by MTProto.
 *
 * PHP's OpenSSL has no IGE mode, so IGE is built on top of AES-256-ECB
 * block by block, exactly like MadelineProto's Crypt::igeEncrypt/igeDecrypt:
 *
 *   encrypt: C_i = E(P_i XOR C_{i-1}') XOR P'_{i-1}  (split IV into two halves)
 *   decrypt: P_i = D(C_i XOR P'_{i-1}) XOR C_{i-1}'
 *
 * @see https://core.telegram.org/mtproto/description
 */
final class AesIge
{
    /**
     * Encrypt with AES-256-IGE.
     *
     * @param string $plain Plaintext; length must be a multiple of 16.
     * @param string $key   32-byte key.
     * @param string $iv    32-byte IV.
     * @return string Ciphertext (same length as plaintext).
     */
    public static function encrypt(string $plain, string $key, string $iv): string
    {
        $a = substr($iv, 0, 16);
        $b = substr($iv, 16, 16);
        $ct = '';
        $n = strlen($plain);
        for ($i = 0; $i < $n; $i += 16) {
            $p = substr($plain, $i, 16);
            /** @var string $c */
            $c = openssl_encrypt($p ^ $a, 'aes-256-ecb', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING) ^ $b;
            $ct .= $c;
            $a = $c;
            $b = $p;
        }
        return $ct;
    }

    /**
     * Decrypt with AES-256-IGE.
     *
     * @param string $cipher Ciphertext; length must be a multiple of 16.
     * @param string $key    32-byte key.
     * @param string $iv     32-byte IV.
     * @return string Plaintext.
     */
    public static function decrypt(string $cipher, string $key, string $iv): string
    {
        $a = substr($iv, 0, 16);
        $b = substr($iv, 16, 16);
        $pt = '';
        $n = strlen($cipher);
        for ($i = 0; $i < $n; $i += 16) {
            $c = substr($cipher, $i, 16);
            /** @var string $p */
            $p = openssl_decrypt($c ^ $b, 'aes-256-ecb', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING) ^ $a;
            $pt .= $p;
            $a = $c;
            $b = $p;
        }
        return $pt;
    }
}
