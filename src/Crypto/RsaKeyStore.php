<?php

declare(strict_types=1);

namespace Bpt\Crypto;

use Bpt\Codec\TlCodec;

/**
 * Loads PEM-encoded RSA keys and derives MTProto fingerprints.
 */
final class RsaKeyStore
{
    /**
     * @param string[] $pems PEM blocks (PKCS#1 "RSA PUBLIC KEY" or PKIX).
     * @return array<string,RsaKey> Map fingerprint-hex => key.
     * @throws \RuntimeException If a PEM block cannot be parsed.
     */
    public static function fromPemList(array $pems): array
    {
        $out = [];
        foreach ($pems as $pem) {
            $res = openssl_pkey_get_public($pem);
            if ($res === false) {
                throw new \RuntimeException('Invalid RSA PEM block');
            }
            /** @var array{rsa:array{n:string,e:string}} $d */
            $d = openssl_pkey_get_details($res);
            $n = str_pad($d['rsa']['n'], 256, "\0", STR_PAD_LEFT);
            $e = $d['rsa']['e'];
            $fp = substr(sha1(TlCodec::encodeBytes($n) . TlCodec::encodeBytes($e), true), -8);
            $key = new RsaKey($n, $e, $fp);
            $out[$key->fingerprintHex()] = $key;
        }
        return $out;
    }
}
