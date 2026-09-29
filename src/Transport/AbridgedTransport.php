<?php

declare(strict_types=1);

namespace Bpt\Transport;

use Bpt\Crypto\AesCtr;

/**
 * Abridged MTProto transport over TCP.
 *
 * Client sends 0xEF once, then frames each payload as:
 *   len = payload_len / 4  (1 byte, or 0x7F + 3 LE bytes if >= 0x7F)
 *   payload
 *
 * Optional transports (configured through the BPT_PROXY env var):
 *   - SOCKS5:   "socks5://[user:pass@]host:port" or plain "host:port"
 *   - MTProxy:  a https://t.me/proxy?server=..&port=..&secret=.. link,
 *               a tg://proxy?... link, or "mtproxy://host:port?secret=..".
 *     In MTProxy mode the 0xEF tag is not sent; instead a 64-byte
 *     obfuscation header (Telethon's TcpMTProxy scheme) is written and the
 *     whole abridged stream is AES-256-CTR encrypted.
 *
 * @see https://core.telegram.org/mtproto/mtproto-transports#abridged
 */
final class AbridgedTransport
{
    /** @var resource|null */
    private $sock = null;

    private ?string $proxyMode = null;

    private ?string $proxyHost = null;
    private int $proxyPort = 0;
    private ?string $proxyUser = null;
    private ?string $proxyPass = null;

    private ?string $mtSecret = null;
    private int $dcId = 2;
    private ?AesCtr $enc = null;
    private ?AesCtr $dec = null;

    /**
     * @param string|null $proxy     Proxy config; defaults to the BPT_PROXY env var.
     * @param string|null $proxyUser Defaults to BPT_PROXY_USER.
     * @param string|null $proxyPass Defaults to BPT_PROXY_PASS.
     * @param int         $dcId      Target DC id (MTProxy header); defaults to 2.
     */
    public function __construct(?string $proxy = null, ?string $proxyUser = null, ?string $proxyPass = null, int $dcId = 2)
    {
        $this->dcId = $dcId;
        $proxy ??= self::env('BPT_PROXY');
        $proxyUser ??= self::env('BPT_PROXY_USER');
        $proxyPass ??= self::env('BPT_PROXY_PASS');

        if ($proxy !== null && $proxy !== '') {
            $this->parseProxy($proxy, $proxyUser, $proxyPass);
        }
    }

    private static function env(string $name): ?string
    {
        $v = getenv($name);

        return ($v !== false && $v !== '') ? $v : null;
    }

    /**
     * Connect to a DC, directly or through the configured proxy.
     *
     * @param string $ip   Target DC IPv4 address.
     * @param int    $port Target DC TCP port (usually 443).
     * @param float  $timeout Seconds.
     * @throws \RuntimeException On connection failure.
     */
    public function connect(string $ip, int $port, float $timeout = 15.0): void
    {
        if ($this->proxyMode === 'mtproxy') {
            $s = @stream_socket_client("tcp://{$this->proxyHost}:{$this->proxyPort}", $en, $es, $timeout);
            if ($s === false) {
                throw new \RuntimeException("proxy connect {$this->proxyHost}:{$this->proxyPort} failed: $es ($en)");
            }
            stream_set_timeout($s, (int)$timeout);
            $this->sock = $s;
            fwrite($s, $this->mtProxyHeader());

            return;
        }

        if ($this->proxyMode === 'socks5') {
            $s = @stream_socket_client("tcp://{$this->proxyHost}:{$this->proxyPort}", $en, $es, $timeout);
            if ($s === false) {
                throw new \RuntimeException("proxy connect {$this->proxyHost}:{$this->proxyPort} failed: $es ($en)");
            }
            stream_set_timeout($s, (int)$timeout);
            $this->socks5Handshake($s, $ip, $port);
        } else {
            $s = @stream_socket_client("tcp://$ip:$port", $en, $es, $timeout);
            if ($s === false) {
                throw new \RuntimeException("connect $ip:$port failed: $es ($en)");
            }
            stream_set_timeout($s, (int)$timeout);
        }
        fwrite($s, "\xEF");
        $this->sock = $s;
    }

    /**
     * Decide whether the proxy string is an MTProxy link or a SOCKS5 address.
     *
     * @throws \RuntimeException On a malformed address.
     */
    private function parseProxy(string $proxy, ?string $user, ?string $pass): void
    {
        if (str_contains($proxy, 't.me/proxy')
            || str_contains($proxy, 'tg://proxy')
            || str_starts_with($proxy, 'mtproxy://')
        ) {
            $this->parseMtProxy($proxy);

            return;
        }

        $this->proxyMode = 'socks5';

        if (str_contains($proxy, '://')) {
            [$scheme, $proxy] = explode('://', $proxy, 2);
            $scheme = strtolower($scheme);
            if (!in_array($scheme, ['socks5', 'socks5h'], true)) {
                throw new \RuntimeException("unsupported proxy scheme: $scheme (only socks5/socks5h)");
            }
        }

        if (str_contains($proxy, '@')) {
            [$creds, $proxy] = explode('@', $proxy, 2);
            if ($user === null || $user === '') {
                [$user, $pass] = array_pad(explode(':', $creds, 2), 2, null);
            }
        }

        if (!preg_match('/^\[([^\]]+)\]:(\d+)$/', $proxy, $m)
            && !preg_match('/^([^:]+):(\d+)$/', $proxy, $m)
        ) {
            throw new \RuntimeException("malformed proxy address: $proxy (expected host:port)");
        }

        $this->proxyHost = $m[1];
        $this->proxyPort = (int)$m[2];
        $this->proxyUser = ($user !== null && $user !== '') ? $user : null;
        $this->proxyPass = $pass;
    }

    /**
     * Parse a t.me / tg:// / mtproxy:// link into host + port + secret.
     *
     * @throws \RuntimeException On a malformed link.
     */
    private function parseMtProxy(string $link): void
    {
        if (str_starts_with($link, 'tg://')) {
            $link = 'https://' . substr($link, 5);
        }

        $server = null;
        $port = 0;
        $secret = null;

        if (str_starts_with($link, 'mtproxy://')) {
            $p = parse_url($link);
            if (is_array($p)) {
                $server = $p['host'] ?? null;
                $port = (int)($p['port'] ?? 0);
                parse_str($p['query'] ?? '', $q);
                $secret = $q['secret'] ?? null;
            }
        } else {
            $query = parse_url($link, PHP_URL_QUERY);
            parse_str((string)$query, $params);
            $server = $params['server'] ?? null;
            $port = (int)($params['port'] ?? 0);
            $secret = $params['secret'] ?? null;
        }

        $secret ??= self::env('BPT_PROXY_SECRET');

        if ($server === null || $server === '' || $port === 0 || $secret === null || $secret === '') {
            throw new \RuntimeException('malformed MTProxy link (need server, port, secret)');
        }

        $this->proxyMode = 'mtproxy';
        $this->proxyHost = $server;
        $this->proxyPort = $port;
        $this->mtSecret = self::normalizeSecret($secret);
    }

    /**
     * Decode a secret (hex or base64) into its 16 raw bytes.
     *
     * @throws \RuntimeException On an unsupported (dd) or invalid secret.
     */
    private static function normalizeSecret(string $secret): string
    {
        if (in_array(substr($secret, 0, 2), ['ee', 'dd'], true)) {
            $secret = substr($secret, 2);
        }

        $raw = @hex2bin($secret);
        if ($raw === false) {
            $secret .= str_repeat('=', (4 - strlen($secret) % 4) % 4);
            $raw = base64_decode($secret, true);
        }
        if ($raw === false || strlen($raw) < 16) {
            throw new \RuntimeException('invalid MTProxy secret (need 16 bytes hex or base64)');
        }

        return substr($raw, 0, 16);
    }

    /**
     * Build the 64-byte MTProxy obfuscation header and the CTR ciphers.
     *
     * @see Telethon \Telethon\Network\Connection\tcpmtproxy.py
     * @throws \RuntimeException On an unsupported dd-secret.
     */
    private function mtProxyHeader(): string
    {
        $secret = (string)$this->mtSecret;

        // Generate 64 random bytes that do not look like another protocol.
        do {
            $r = random_bytes(64);
        } while (
            $r[0] === "\xEF"
            || in_array(substr($r, 0, 4), ['PVrG', 'GET ', 'POST', "\xEE\xEE\xEE\xEE"], true)
            || substr($r, 4, 4) === "\0\0\0\0"
        );

        // random[55:7:-1] in Python == reverse of random[8:56].
        $reversed = strrev(substr($r, 8, 48));

        $enc = new AesCtr(hash('sha256', substr($r, 8, 32) . $secret, true), substr($r, 40, 16));
        $dec = new AesCtr(hash('sha256', substr($reversed, 0, 32) . $secret, true), substr($reversed, 32, 16));

        // Bytes 56:60 = abridged tag, 60:62 = little-endian DC id.
        $header = substr($r, 0, 56) . "\xEF\xEF\xEF\xEF" . pack('v', $this->dcId) . substr($r, 62, 2);

        // The first 64 keystream bytes are consumed here; only 56:64 is sent.
        $encrypted = $enc->crypt($header);

        $this->enc = $enc;
        $this->dec = $dec;

        return substr($header, 0, 56) . substr($encrypted, 56, 8);
    }

    /**
     * Perform the SOCKS5 (RFC 1928) + username/password (RFC 1929) handshake.
     *
     * @param resource $s
     * @throws \RuntimeException On handshake failure.
     */
    private function socks5Handshake($s, string $ip, int $port): void
    {
        $wantAuth = $this->proxyUser !== null;
        $methods = $wantAuth ? "\x00\x02" : "\x00";
        fwrite($s, "\x05" . chr(strlen($methods)) . $methods);

        $resp = $this->socksRead($s, 2);
        if ($resp[0] !== "\x05") {
            throw new \RuntimeException('socks5: bad version from proxy');
        }
        $method = ord($resp[1]);
        if ($method === 0xFF) {
            throw new \RuntimeException('socks5: no acceptable auth method');
        }
        if ($method === 0x02) {
            $u = (string)$this->proxyUser;
            $p = (string)$this->proxyPass;
            fwrite($s, "\x01" . chr(strlen($u)) . $u . chr(strlen($p)) . $p);
            $auth = $this->socksRead($s, 2);
            if (ord($auth[1]) !== 0x00) {
                throw new \RuntimeException('socks5: authentication failed');
            }
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $addr = "\x01" . inet_pton($ip);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $addr = "\x04" . inet_pton($ip);
        } else {
            $addr = "\x03" . chr(strlen($ip)) . $ip;
        }
        fwrite($s, "\x05\x01\x00" . $addr . pack('n', $port));

        $head = $this->socksRead($s, 4);
        if ($head[0] !== "\x05") {
            throw new \RuntimeException('socks5: bad reply version');
        }
        $rep = ord($head[1]);
        if ($rep !== 0x00) {
            throw new \RuntimeException("socks5: connect to $ip:$port failed (rep=$rep)");
        }
        $atyp = ord($head[3]);
        $skip = match ($atyp) {
            0x01 => 4,
            0x04 => 16,
            0x03 => ord($this->socksRead($s, 1)),
            default => throw new \RuntimeException("socks5: unknown address type $atyp"),
        };
        $this->socksRead($s, $skip + 2);
    }

    /**
     * Read exactly $n bytes from the raw proxy socket.
     *
     * @param resource $s
     * @throws \RuntimeException On disconnect/timeout.
     */
    private function socksRead($s, int $n): string
    {
        $r = '';
        while (strlen($r) < $n) {
            $c = fread($s, $n - strlen($r));
            if ($c === false || $c === '') {
                throw new \RuntimeException('socks5: proxy disconnected during handshake');
            }
            $r .= $c;
        }
        return $r;
    }

    /**
     * Try several candidate IPs in order.
     *
     * @param string[] $ips
     * @return string The IP that connected.
     */
    public function connectAny(array $ips, int $port): string
    {
        $last = '';
        foreach (array_values(array_unique($ips)) as $ip) {
            for ($t = 0; $t < 2; $t++) {
                try {
                    $this->connect($ip, $port);
                    return $ip;
                } catch (\RuntimeException $e) {
                    $last = $e->getMessage();
                    sleep(1);
                }
            }
        }
        throw new \RuntimeException('connect fail: ' . $last);
    }

    /**
     * Send one framed payload (encrypted when tunnelling through MTProxy).
     */
    public function send(string $payload): void
    {
        $len = strlen($payload) >> 2;
        $h = ($len >= 0x7F) ? "\x7F" . substr(pack('V', $len), 0, 3) : chr($len);
        $frame = $h . $payload;
        if ($this->enc !== null) {
            $frame = $this->enc->crypt($frame);
        }
        fwrite($this->sock, $frame);
    }

    /**
     * Receive one framed payload (decrypted when tunnelling through MTProxy).
     *
     * @throws \RuntimeException On timeout / disconnect.
     */
    public function receive(): string
    {
        $b = ord($this->readPlain(1));
        $len = ($b === 0x7F) ? unpack('V', $this->readPlain(3) . "\0")[1] : $b;
        return $this->readPlain($len << 2);
    }

    /**
     * Read $n bytes from the socket and decrypt them in the CTR stream.
     *
     * @throws \RuntimeException
     */
    private function readPlain(int $n): string
    {
        $raw = $this->readN($n);
        return $this->dec !== null ? $this->dec->crypt($raw) : $raw;
    }

    /**
     * @throws \RuntimeException
     */
    private function readN(int $n): string
    {
        $r = '';
        while (strlen($r) < $n) {
            $c = fread($this->sock, $n - strlen($r));
            if ($c === false || $c === '') {
                throw new \RuntimeException('disconnect/timeout');
            }
            $r .= $c;
        }
        return $r;
    }

    /**
     * Close the socket (idempotent).
     */
    public function close(): void
    {
        if (is_resource($this->sock)) {
            fclose($this->sock);
        }
        $this->sock = null;
        $this->enc = null;
        $this->dec = null;
    }

    /**
     * Set read timeout for subsequent receives.
     */
    public function setTimeout(int $seconds): void
    {
        if (is_resource($this->sock)) {
            stream_set_timeout($this->sock, $seconds);
        }
    }
}
