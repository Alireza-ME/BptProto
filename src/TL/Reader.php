<?php

declare(strict_types=1);

namespace Bpt\TL;

use Bpt\Codec\TlCodec;

/**
 * Offset-based TL deserializer.
 *
 * Complements {@see Builder}: sequential reads with bounds checking.
 * All methods advance the internal offset (TL padding included).
 */
final class Reader
{
    private int $off = 0;

    public function __construct(private readonly string $buf)
    {
    }

    public static function of(string $buf): self
    {
        return new self($buf);
    }

    public function remaining(): int
    {
        return strlen($this->buf) - $this->off;
    }

    public function eof(): bool
    {
        return $this->off >= strlen($this->buf);
    }

    public function offset(): int
    {
        return $this->off;
    }

    public function ctor(): int
    {
        return $this->int();
    }

    public function int(): int
    {
        $this->need(4);
        $v = TlCodec::unpackInt($this->buf, $this->off);
        $this->off += 4;
        return $v;
    }

    /**
     * Read a signed 64-bit little-endian integer (exact via GMP:
     * access_hashes use the full 64-bit range).
     */
    public function long(): int
    {
        $this->need(8);
        $lo = TlCodec::unpackInt($this->buf, $this->off);
        $hi = TlCodec::unpackInt($this->buf, $this->off + 4);
        $this->off += 8;
        $v = gmp_add(gmp_mul($hi, 4294967296), $lo);
        if (gmp_cmp($v, '9223372036854775807') > 0) {
            $v = gmp_sub($v, '18446744073709551616'); // two's complement
        }
        return gmp_intval($v);
    }

    public function bytes(): string
    {
        $off = $this->off;
        $v = TlCodec::decodeBytes($this->buf, $off);
        $this->off = $off;
        return $v;
    }

    public function string(): string
    {
        return $this->bytes();
    }

    public function bool(): bool
    {
        $ctor = $this->int();
        if ($ctor === 0x997275B5) {
            return true;
        }
        if ($ctor === 0xBC799737) {
            return false;
        }
        throw new \RuntimeException('expected bool, got 0x' . dechex($ctor));
    }

    /**
     * Read a 64-bit IEEE-754 double (little-endian).
     */
    public function double(): float
    {
        $this->need(8);
        /** @var array{1:float} $v */
        $v = unpack('e', substr($this->buf, $this->off, 8));
        $this->off += 8;
        return $v[1];
    }

    /**
     * Read $n raw bytes (for int128/int256 fields), advancing the offset.
     */
    public function rawBytes(int $n): string
    {
        $this->need($n);
        $v = substr($this->buf, $this->off, $n);
        $this->off += $n;
        return $v;
    }

    /**
     * Read a vector header, return element count.
     *
     * @throws \RuntimeException If the vector constructor is missing.
     */
    public function vectorHeader(): int
    {
        $ctor = $this->int();
        if ($ctor !== 0x1CB5C415) {
            throw new \RuntimeException('expected vector, got 0x' . dechex($ctor));
        }
        return $this->int();
    }

    /** @return int[] */
    public function vectorInt(): array
    {
        $n = $this->vectorHeader();
        $out = [];
        for ($i = 0; $i < $n; $i++) {
            $out[] = $this->int();
        }
        return $out;
    }

    /** @return int[] */
    public function vectorLong(): array
    {
        $n = $this->vectorHeader();
        $out = [];
        for ($i = 0; $i < $n; $i++) {
            $out[] = $this->long();
        }
        return $out;
    }

    /** @return string[] */
    public function vectorString(): array
    {
        $n = $this->vectorHeader();
        $out = [];
        for ($i = 0; $i < $n; $i++) {
            $out[] = $this->string();
        }
        return $out;
    }

    /**
     * Read $n raw objects: each is returned as [ctor, body-slice-start..].
     * Caller parses each object with its own Reader over the remaining bytes.
     * Here we just expose the underlying buffer + offset for manual parsing.
     */
    public function raw(): string
    {
        return $this->buf;
    }

    private function need(int $n): void
    {
        if ($this->off + $n > strlen($this->buf)) {
            throw new \RuntimeException('TL buffer overrun');
        }
    }
}
