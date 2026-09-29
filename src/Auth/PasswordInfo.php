<?php

declare(strict_types=1);

namespace Bpt\Auth;

use Bpt\Codec\TlCodec;

/**
 * Parsed account.password structure needed for SRP.
 */
final class PasswordInfo
{
    /**
     * @param string $salt1  Server salt1.
     * @param string $salt2  Server salt2.
     * @param int    $g      Generator.
     * @param string $p      Prime (big-endian bytes).
     * @param string $srpB   Server B (bytes).
     * @param string $srpIdRaw 8-byte little-endian srp_id (verbatim for replay).
     */
    public function __construct(
        public readonly string $salt1,
        public readonly string $salt2,
        public readonly int $g,
        public readonly string $p,
        public readonly string $srpB,
        public readonly string $srpIdRaw,
    ) {
    }

    /**
     * Parse account.password#957b50fb up to srp_id (rest ignored).
     *
     * @throws \RuntimeException If there is no password or parsing fails.
     */
    public static function parse(string $b): self
    {
        $off = 0;
        $ctor = TlCodec::unpackInt($b, $off);
        $off += 4;
        if ($ctor !== 0x957B50FB) {
            throw new \RuntimeException('expected account.password, got 0x' . dechex($ctor));
        }
        $flags = TlCodec::unpackInt($b, $off);
        $off += 4;
        if (!($flags & 4)) {
            throw new \RuntimeException('no 2FA password set');
        }
        $off += 4; // algo ctor (e.g. SRP id), validated implicitly
        $salt1 = TlCodec::decodeBytes($b, $off);
        $salt2 = TlCodec::decodeBytes($b, $off);
        $g = TlCodec::unpackInt($b, $off);
        $off += 4;
        $p = TlCodec::decodeBytes($b, $off);
        $srpB = TlCodec::decodeBytes($b, $off);
        return new self($salt1, $salt2, $g, $p, $srpB, substr($b, $off, 8));
    }
}
