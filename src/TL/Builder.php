<?php

declare(strict_types=1);

namespace Bpt\TL;

use Bpt\Codec\TlCodec;

/**
 * Fluent TL serializer.
 *
 * Wraps {@see TlCodec} primitives and adds the missing TL types so every
 * handwritten API method builds its body the same way:
 *
 * ```php
 * $body = Builder::ctor(0x545CD15A)
 *     ->int(0)                       // flags
 *     ->bytes(Peer::self())          // already-packed InputPeer
 *     ->string($text)
 *     ->long($randomId)
 *     ->build();
 * ```
 *
 * Raw packed blobs (peers, vectors built elsewhere) go through bytesRaw()
 * to avoid double length-prefixing.
 */
final class Builder
{
    private string $buf = '';

    private function __construct(string $seed = '')
    {
        $this->buf = $seed;
    }

    public static function ctor(int $id): self
    {
        return new self(TlCodec::packInt($id));
    }

    public static function raw(string $bytes): self
    {
        return new self($bytes);
    }

    public function int(int $v): self
    {
        $this->buf .= TlCodec::packInt($v);
        return $this;
    }

    public function long(int $v): self
    {
        $this->buf .= TlCodec::packLong($v);
        return $this;
    }

    public function double(float $v): self
    {
        $this->buf .= pack('e', $v);
        return $this;
    }

    public function bool(bool $v): self
    {
        // boolFalse#bc799737 / boolTrue#997275b5
        $this->buf .= TlCodec::packInt($v ? 0x997275B5 : 0xBC799737);
        return $this;
    }

    public function string(string $v): self
    {
        $this->buf .= TlCodec::encodeBytes($v);
        return $this;
    }

    /** Alias of string(): TL string == bytes on the wire. */
    public function bytes(string $v): self
    {
        return $this->string($v);
    }

    /** Append an already-TL-packed blob (InputPeer, nested object...). */
    public function rawBlob(string $packed): self
    {
        $this->buf .= $packed;
        return $this;
    }

    /**
     * Pack a vector of already-packed items.
     *
     * @param string[] $items Each item already TL-serialized.
     */
    public function vector(array $items): self
    {
        $this->buf .= TlCodec::packInt(0x1CB5C415) . TlCodec::packInt(count($items));
        foreach ($items as $it) {
            $this->buf .= $it;
        }
        return $this;
    }

    public function vectorInt(array $ints): self
    {
        $packed = [];
        foreach ($ints as $v) {
            $packed[] = TlCodec::packInt((int)$v);
        }
        return $this->vector($packed);
    }

    public function vectorLong(array $longs): self
    {
        $packed = [];
        foreach ($longs as $v) {
            $packed[] = TlCodec::packLong((int)$v);
        }
        return $this->vector($packed);
    }

    public function vectorString(array $strings): self
    {
        $packed = [];
        foreach ($strings as $s) {
            $packed[] = TlCodec::encodeBytes((string)$s);
        }
        return $this->vector($packed);
    }

    public function vectorDouble(array $doubles): self
    {
        $packed = [];
        foreach ($doubles as $v) {
            $packed[] = pack('e', (float)$v);
        }
        return $this->vector($packed);
    }

    /** Alias of vectorString(): TL bytes and string share the wire format. */
    public function vectorBytes(array $bytes): self
    {
        return $this->vectorString($bytes);
    }

    public function build(): string
    {
        return $this->buf;
    }
}
