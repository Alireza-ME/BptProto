<?php

declare(strict_types=1);

namespace Bpt\Crypto;

/**
 * RSA server key used to encrypt p_q_inner_data during the DH handshake.
 *
 * Fingerprint = last 8 bytes of SHA1(TL(n) . TL(e)), sent verbatim
 * (MTProto does NOT byte-swap hash-derived longs).
 */
final class RsaKey
{
    /**
     * @param string $n  256-byte big-endian modulus.
     * @param string $e  Big-endian exponent (usually 0x010001).
     * @param string $fp 8-byte fingerprint (raw SHA1 tail).
     */
    public function __construct(
        public readonly string $n,
        public readonly string $e,
        public readonly string $fp,
    ) {
    }

    /**
     * Fingerprint as lowercase hex (big-endian display order).
     */
    public function fingerprintHex(): string
    {
        return strtolower(bin2hex($this->fp));
    }
}
