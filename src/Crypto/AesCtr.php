<?php

declare(strict_types=1);

namespace Bpt\Crypto;

/**
 * Streaming AES-256-CTR matching MTProto / MTProxy (pyaes / Telethon).
 *
 * The 16-byte IV is the initial counter block and is incremented as a
 * big-endian 128-bit integer, one block per 16 bytes of keystream. The
 * stream position is preserved across calls, so it can be used both for the
 * 64-byte MTProxy obfuscation header and the payload that follows it.
 */
final class AesCtr
{
    private string $keystream = '';

    public function __construct(
        private readonly string $key,
        private string $counter,
    ) {
    }

    /**
     * Encrypt or decrypt $data (identical for CTR) and advance the stream.
     */
    public function crypt(string $data): string
    {
        $len = strlen($data);
        if ($len === 0) {
            return '';
        }

        $missing = $len - strlen($this->keystream);
        if ($missing > 0) {
            $blocks = intdiv($missing + 15, 16);
            $ctr = '';
            for ($i = 0; $i < $blocks; $i++) {
                $ctr .= $this->counter;
                $this->increment();
            }
            $this->keystream .= (string)openssl_encrypt(
                $ctr,
                'aes-256-ecb',
                $this->key,
                OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            );
        }

        $ks = substr($this->keystream, 0, $len);
        $this->keystream = substr($this->keystream, $len);

        return $data ^ $ks;
    }

    /**
     * Increment the counter block as a 128-bit big-endian integer.
     */
    private function increment(): void
    {
        for ($i = 15; $i >= 0; $i--) {
            $b = (ord($this->counter[$i]) + 1) & 0xFF;
            $this->counter[$i] = chr($b);
            if ($b !== 0) {
                return;
            }
        }
    }
}
