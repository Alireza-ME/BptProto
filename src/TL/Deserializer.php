<?php

declare(strict_types=1);

namespace Bpt\TL;

/**
 * Schema-driven TL deserializer.
 *
 * Turns any raw TL object (constructor + fields) into a plain nested array,
 * Telethon-style: every boxed object becomes ['_' => constructorName, ...],
 * vectors become lists and primitives become scalars. The constructor layout
 * comes from the generated {@see Types} map (see tools/gen_methods.php).
 *
 *   $r = Deserializer::parse($rawUpdates, 'Updates');
 *   // ['_' => 'updates', 'updates' => [ ['_'=>'updateNewMessage', ...] ], ...]
 */
final class Deserializer
{
    /**
     * Parse a raw result blob of the given TL type.
     *
     * @param string $raw  Inner rpc_result body (constructor + fields).
     * @param string $type Declared result type (e.g. 'Updates', 'Bool', 'Vector<int>').
     */
    public static function parse(string $raw, string $type): mixed
    {
        return self::parseValue($type, Reader::of($raw));
    }

    /**
     * Parse one value of $type from the reader (dispatches on primitive /
     * Vector / boxed).
     */
    public static function parseValue(string $type, Reader $r): mixed
    {
        switch ($type) {
            case 'int':
                return $r->int();
            case 'long':
                return $r->long();
            case 'double':
                return $r->double();
            case 'int128':
                return $r->rawBytes(16);
            case 'int256':
                return $r->rawBytes(32);
            case 'string':
            case 'bytes':
                return $r->bytes();
            case 'Bool':
                return $r->int() === 0x997275B5;
            default:
                if (str_starts_with($type, 'Vector<')) {
                    return self::parseVector(substr($type, 7, -1), $r);
                }
                return self::parseCtor($r->int(), $r);
        }
    }

    /**
     * Parse one boxed object (constructor id has already been read).
     *
     * @return array{_:string}&array<string,mixed>
     */
    public static function parseCtor(int $ctor, Reader $r): array
    {
        $def = Types::map()[$ctor] ?? null;
        if ($def === null) {
            throw new \RuntimeException('unknown TL constructor 0x' . dechex($ctor));
        }
        [$typeName, $name, $fields] = $def;
        $out = ['_' => $name];
        $flags = ['flags' => 0, 'flags2' => 0];
        foreach ($fields as $f) {
            switch ($f[0]) {
                case 'F':
                    $flags[$f[1]] = $r->int();
                    break;
                case 'B':
                    $out[$f[1]] = (bool)($flags[$f[3]] & (1 << $f[2]));
                    break;
                case 'O':
                    if ($flags[$f[4]] & (1 << $f[3])) {
                        $out[$f[1]] = self::parseValue($f[2], $r);
                    }
                    break;
                default: // 'R'
                    $out[$f[1]] = self::parseValue($f[2], $r);
            }
        }
        return $out;
    }

    /**
     * @return array<int,mixed>
     */
    private static function parseVector(string $inner, Reader $r): array
    {
        $r->int(); // vector#1cb5c415
        $n = $r->int();
        $out = [];
        for ($i = 0; $i < $n; $i++) {
            $out[] = self::parseValue($inner, $r);
        }
        return $out;
    }
}
