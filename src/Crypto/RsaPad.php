<?php

declare(strict_types=1);

namespace Bpt\Crypto;

/**
 * MTProto RSA_PAD construction (§4.1 of the auth_key docs).
 *
 *  1. pad data to 192 bytes with random bytes
 *  2. reverse, append SHA256(temp_key + padded)
 *  3. AES-256-IGE with temp_key and zero IV
 *  4. adjust temp_key with SHA256 of the ciphertext
 *  5. RSA-encrypt the 256-byte block (must be < N, else retry)
 */
final class RsaPad
{
    /**
     * @param string $data TL-serialized p_q_inner_data (<= 144 bytes).
     * @return string 256-byte RSA ciphertext (big-endian).
     */
    public static function encrypt(string $data, RsaKey $key): string
    {
        $N = gmp_import($key->n);
        $E = gmp_import($key->e);
        while (true) {
            $withPad = $data . random_bytes(192 - strlen($data));
            $tk = random_bytes(32);
            $withHash = strrev($withPad) . hash('sha256', $tk . $withPad, true);
            $enc = AesIge::encrypt($withHash, $tk, str_repeat("\0", 32));
            $both = ($tk ^ hash('sha256', $enc, true)) . $enc;
            if (gmp_cmp(gmp_import($both), $N) < 0) {
                return str_pad(gmp_export(gmp_powm(gmp_import($both), $E, $N)), 256, "\0", STR_PAD_LEFT);
            }
        }
    }
}
