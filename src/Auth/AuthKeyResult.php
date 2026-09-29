<?php

declare(strict_types=1);

namespace Bpt\Auth;

/**
 * Result of the Diffie-Hellman authorization-key handshake.
 */
final class AuthKeyResult
{
    /**
     * @param string $authKey 256-byte authorization key (big-endian).
     * @param string $salt    8-byte server salt (new_nonce[0:8] XOR server_nonce[0:8]).
     * @param int    $timeOffset server_time - time().
     * @param string $keyId   8-byte auth_key_id (SHA1(auth_key)[12:20]).
     */
    public function __construct(
        public readonly string $authKey,
        public readonly string $salt,
        public readonly int $timeOffset,
        public readonly string $keyId,
    ) {
    }
}
