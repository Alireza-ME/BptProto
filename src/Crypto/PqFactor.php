<?php

declare(strict_types=1);

namespace Bpt\Crypto;

/**
 * Pollard-Rho factorization for the small (<= 2^63-1) pq from resPQ.
 *
 * Uses GMP arithmetic; more than fast enough for 63-bit composites.
 */
final class PqFactor
{
    /**
     * @param \GMP $n Composite pq.
     * @return array{0:\GMP,1:\GMP} [p, q] with p < q.
     */
    public static function factor(\GMP $n): array
    {
        $p = self::rho($n);
        $q = gmp_div($n, $p);
        if (gmp_cmp($p, $q) > 0) {
            [$p, $q] = [$q, $p];
        }
        return [$p, $q];
    }

    /**
     * @param \GMP $n Odd composite.
     * @return \GMP Non-trivial divisor.
     */
    private static function rho(\GMP $n): \GMP
    {
        if (gmp_cmp(gmp_mod($n, 2), 0) == 0) {
            return gmp_init(2);
        }
        while (true) {
            $x = gmp_init(random_int(2, 1_000_000));
            $y = $x;
            $c = gmp_init(random_int(1, 1_000_000));
            $d = gmp_init(1);
            while (gmp_cmp($d, 1) == 0) {
                $x = gmp_mod(gmp_add(gmp_mul($x, $x), $c), $n);
                $y = gmp_mod(gmp_add(gmp_mul($y, $y), $c), $n);
                $y = gmp_mod(gmp_add(gmp_mul($y, $y), $c), $n);
                $d = gmp_gcd(gmp_abs(gmp_sub($x, $y)), $n);
            }
            if (gmp_cmp($d, $n) != 0) {
                return $d;
            }
        }
    }
}
