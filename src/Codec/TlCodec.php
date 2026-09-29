<?php

declare(strict_types=1);

namespace Bpt\Codec;

/**
 * TL (Type Language) binary codec.
 *
 * Implements the subset of MTProto serialization used by the auth_key
 * handshake and the RPC calls:
 *  - int (4 bytes, little-endian)
 *  - long (8 bytes, little-endian)
 *  - bytes/string (1-byte or 0xFE extended length + padding to 4)
 *
 * @see https://core.telegram.org/mtproto/serialize
 */
final class TlCodec
{
    /**
     * Serialize a byte string in TL format.
     *
     * @param string $data Raw bytes.
     * @return string TL-encoded bytes (length prefix + data + zero padding).
     */
    public static function encodeBytes(string $data): string
    {
        $len = strlen($data);
        if ($len < 254) {
            $s = chr($len) . $data;
            return $s . str_repeat("\0", (4 - strlen($s) % 4) % 4);
        }
        $s = "\xFE" . substr(pack('V', $len), 0, 3) . $data;
        return $s . str_repeat("\0", (4 - strlen($s) % 4) % 4);
    }

    /**
     * Decode a TL byte string.
     *
     * @param string $buf  Input buffer.
     * @param int    $off  Offset (bytes); advanced past the value including padding.
     * @return string Raw decoded bytes.
     * @throws \InvalidArgumentException If the buffer is truncated.
     */
    public static function decodeBytes(string $buf, int &$off): string
    {
        if ($off >= strlen($buf)) {
            throw new \InvalidArgumentException('TL buffer overrun');
        }
        $first = ord($buf[$off]);
        if ($first < 254) {
            $len = $first;
            $off += 1;
            $r = substr($buf, $off, $len);
            $off += $len;
            $off += (4 - (1 + $len) % 4) % 4;
            return $r;
        }
        $len = unpack('V', substr($buf, $off + 1, 3) . "\0")[1];
        $off += 4;
        $r = substr($buf, $off, $len);
        $off += $len;
        $off += (4 - $len % 4) % 4;
        return $r;
    }

    /**
     * Pack a 32-bit unsigned int as little-endian.
     */
    public static function packInt(int $v): string
    {
        return pack('V', $v & 0xFFFFFFFF);
    }

    /**
     * Unpack a 32-bit little-endian unsigned int.
     */
    public static function unpackInt(string $buf, int $off = 0): int
    {
        /** @var array{1:int} $a */
        $a = unpack('V', substr($buf, $off, 4));
        return $a[1];
    }

    /**
     * Pack a 64-bit signed value as little-endian (two's complement,
     * so negative access_hashes round-trip exactly).
     */
    public static function packLong(int $v): string
    {
        $low = $v & 0xFFFFFFFF;
        $high = ($v >> 32) & 0xFFFFFFFF;
        return pack('V', $low) . pack('V', $high);
    }
}
