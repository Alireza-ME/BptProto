<?php

declare(strict_types=1);

namespace Bpt\Crypto;

/**
 * Telegram SRP (2FA password) verification.
 *
 * Port of gotd/td crypto/srp using GMP + hash_pbkdf2:
 *   H   = SHA256
 *   SH  = H(salt | data | salt)
 *   PH1 = SH(SH(password, salt1), salt2)
 *   PH2 = SH(PBKDF2-HMAC-SHA512(PH1, salt1, 100000), salt2)
 *
 * @see https://core.telegram.org/api/srp
 */
final class Srp
{
    /**
     * Compute SRP answer for auth.checkPassword.
     *
     * @param string $password UTF-8 password.
     * @param string $salt1    Server salt1 (bytes).
     * @param string $salt2    Server salt2 (bytes).
     * @param int    $g        Generator (small int).
     * @param string $pBin     256-byte big-endian prime.
     * @param string $srpB     Server srp_B (bytes).
     * @return array{0:string,1:string} [A(256 bytes), M1(32 bytes)].
     */
    public static function compute(string $password, string $salt1, string $salt2, int $g, string $pBin, string $srpB): array
    {
        $h = static fn(string ...$d): string => hash('sha256', implode('', $d), true);
        $sh = static fn(string $d, string $s): string => $h($s . $d . $s);

        $P = gmp_import($pBin);
        $G = gmp_init($g);
        $gBytes = str_pad(gmp_export($G), 256, "\0", STR_PAD_LEFT);
        $pPad = str_pad($pBin, 256, "\0", STR_PAD_LEFT);

        $ph1 = $sh($sh($password, $salt1), $salt2);
        $xBin = $sh(hash_pbkdf2('sha512', $ph1, $salt1, 100_000, 64, true), $salt2);
        $X = gmp_import($xBin);
        $V = gmp_powm($G, $X, $P);

        $aBin = random_bytes(256);
        $Aint = gmp_import($aBin);
        $gA = str_pad(gmp_export(gmp_powm($G, $Aint, $P)), 256, "\0", STR_PAD_LEFT);
        $gB = str_pad($srpB, 256, "\0", STR_PAD_LEFT);

        $U = gmp_import($h($gA, $gB));
        $K = gmp_import($h($pPad, $gBytes));
        $kv = gmp_mod(gmp_mul($K, $V), $P);
        $T = gmp_sub(gmp_import($srpB), $kv);
        if (gmp_cmp($T, 0) < 0) {
            $T = gmp_add($T, $P);
        }
        $sa = str_pad(gmp_export(gmp_powm($T, gmp_add($Aint, gmp_mul($U, $X)), $P)), 256, "\0", STR_PAD_LEFT);
        $ka = $h($sa);
        $m1 = $h($h($pPad) ^ $h($gBytes), $h($salt1), $h($salt2), $gA, $gB, $ka);
        return [$gA, $m1];
    }
}
