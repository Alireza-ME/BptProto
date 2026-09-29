<?php

declare(strict_types=1);

namespace Bpt;

/**
 * Static protocol data: DC IP map, API layer and the vendored RSA keys.
 *
 * Application credentials (api id/hash, test mode, DC) belong to the
 * client, not here: see {@see Client}.
 */
final class Config
{
    /**
     * Default Telegram API layer.
     *
     * Must match the bundled schema (tools/telegram_api.tl, tdlib master):
     * layer 229 → user#b1b8cc83, message#95ef6f2b, ... Requesting a different
     * layer makes the server return constructor ids the generated map does
     * not know (e.g. layer 225 returns legacy user#31774388).
     */
    public const DEFAULT_LAYER = 229;

    /**
     * Production / test DC IPs.
     *
     * @return array<int,string> Map dcId => ip.
     */
    public static function dcMap(bool $isTest): array
    {
        return $isTest
            ? [1 => '149.154.175.10', 2 => '149.154.167.40', 3 => '149.154.175.117']
            : [1 => '149.154.175.52', 2 => '149.154.167.41', 3 => '149.154.175.100', 4 => '149.154.167.91', 5 => '91.108.56.191'];
    }

    /**
     * Vendored RSA public keys (gotd/td _data/public_keys.pem).
     *
     * @return string[]
     */
    public static function rsaPems(): array
    {
        return [
            <<<PEM
            -----BEGIN RSA PUBLIC KEY-----
            MIIBCgKCAQEAyMEdY1aR+sCR3ZSJrtztKTKqigvO/vBfqACJLZtS7QMgCGXJ6XIR
            yy7mx66W0/sOFa7/1mAZtEoIokDP3ShoqF4fVNb6XeqgQfaUHd8wJpDWHcR2OFwv
            plUUI1PLTktZ9uW2WE23b+ixNwJjJGwBDJPQEQFBE+vfmH0JP503wr5INS1poWg/
            j25sIWeYPHYeOrFp/eXaqhISP6G+q2IeTaWTXpwZj4LzXq5YOpk4bYEQ6mvRq7D1
            aHWfYmlEGepfaYR8Q0YqvvhYtMte3ITnuSJs171+GDqpdKcSwHnd6FudwGO4pcCO
            j4WcDuXc2CTHgH8gFTNhp/Y8/SpDOhvn9QIDAQAB
            -----END RSA PUBLIC KEY-----
            PEM,
            <<<PEM
            -----BEGIN RSA PUBLIC KEY-----
            MIIBCgKCAQEA6LszBcC1LGzyr992NzE0ieY+BSaOW622Aa9Bd4ZHLl+TuFQ4lo4g
            5nKaMBwK/BIb9xUfg0Q29/2mgIR6Zr9krM7HjuIcCzFvDtr+L0GQjae9H0pRB2OO
            62cECs5HKhT5DZ98K33vmWiLowc621dQuwKWSQKjWf50XYFw42h21P2KXUGyp2y/
            +aEyZ+uVgLLQbRA1dEjSDZ2iGRy12Mk5gpYc397aYp438fsJoHIgJ2lgMv5h7WY9
            t6N/byY9Nw9p21Og3AoXSL2q/2IJ1WRUhebgAdGVMlV1fkuOQoEzR7EdpqtQD9Cs
            5+bfo3Nhmcyvk5ftB0WkJ9z6bNZ7yxrP8wIDAQAB
            -----END RSA PUBLIC KEY-----
            PEM,
        ];
    }
}
