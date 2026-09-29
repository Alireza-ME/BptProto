<?php

declare(strict_types=1);

namespace Bpt\Entity;

use Bpt\TL\Ctors;
use Bpt\TL\Reader;

/**
 * Minimal User DTO parsed from user#31774388 (or legacy 204 user#020b1422;
 * prefix layout flags,id,hash,names is identical).
 */
final class User
{
    public function __construct(
        public readonly int $id,
        public readonly int $accessHash = 0,
        public readonly string $firstName = '',
        public readonly string $lastName = '',
        public readonly string $username = '',
        public readonly string $phone = '',
        public readonly bool $bot = false,
        public readonly bool $self = false,
    ) {
    }

    public function name(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    /** @return self|null null when ctor is userEmpty or unknown */
    public static function parse(Reader $r): ?self
    {
        $ctor = $r->ctor();
        if ($ctor === Ctors::USER_EMPTY) {
            $r->int(); // id
            return null;
        }
        if (!Ctors::isUser($ctor)) {
            throw new \RuntimeException('expected user, got 0x' . dechex($ctor));
        }
        $legacy = $ctor === 0x020B1422;
        $flags = $r->int();
        $r->int(); // flags2 (present in all observed layers)
        $self = (bool)($flags & (1 << 10));
        $bot = (bool)($flags & (1 << 14));
        $id = $r->long();
        $hash = ($flags & 1) ? $r->long() : 0;
        $first = ($flags & 2) ? $r->string() : '';
        $last = ($flags & 4) ? $r->string() : '';
        $username = ($flags & 8) ? $r->string() : '';
        $phone = ($flags & 16) ? $r->string() : '';
        return new self($id, $hash, $first, $last, $username, $phone, $bot, $self);
    }

    /** @return self[] */
    public static function parseVector(Reader $r): array
    {
        $n = $r->vectorHeader();
        $out = [];
        for ($i = 0; $i < $n; $i++) {
            try {
                $u = self::parse($r);
                if ($u !== null) {
                    $out[] = $u;
                }
            } catch (\Throwable) {
                break;
            }
        }
        return $out;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id, 'access_hash' => $this->accessHash,
            'first_name' => $this->firstName, 'last_name' => $this->lastName,
            'username' => $this->username, 'phone' => $this->phone,
            'bot' => $this->bot, 'self' => $this->self,
        ];
    }
}
