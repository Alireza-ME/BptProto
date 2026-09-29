<?php

declare(strict_types=1);

namespace Bpt;

use Bpt\Api\TelegramApi;
use Bpt\Auth\AuthKeyExchange;
use Bpt\Crypto\RsaKeyStore;
use Bpt\Exception\AuthException;
use Bpt\Exception\FloodWaitException;
use Bpt\Session\EncryptedSession;
use Bpt\Session\RpcErrorException;
use Bpt\Transport\AbridgedTransport;
use Bpt\Transport\MsgIdGenerator;

/**
 * Connection + authorization engine. Single owner of the DC socket, the
 * authorization-key (DH) handshake and the phone login flow
 * (sendCode → signIn → checkPassword), including PHONE_MIGRATE handling and
 * per-DC key caching through {@see Storage}.
 *
 * The high-level API (messages, contacts, …) lives in the {@see BptProto}
 * facade groups — create one with {@see BptProto::create()} or {@see proto()}:
 *
 * ```php
 * $client = new Client($apiId, $apiHash, storage: new Storage('/var/bpt'));
 * $tg = $client->proto();
 * $me = $tg->users->getUsers(['me'])[0];
 * $tg->messages->sendMessage('me', 'hello', random_int(1, PHP_INT_MAX));
 * ```
 *
 * Raw TL for any of the 700+ methods: `$client->rpc($body)` with
 * {@see \Bpt\TL\Builder}.
 */
final class Client
{
    private const KEY_DC = 'dc.txt';
    private const KEY_PHONE = 'phone.txt';
    private const KEY_CODE_HASH = 'phone_hash.txt';
    private const KEY_AUTH_RESULT = 'auth_result.bin';

    private int $dcId;
    private readonly Storage $storage;

    private ?AbridgedTransport $transport = null;
    private ?EncryptedSession $session = null;
    private ?TelegramApi $api = null;
    private ?BptProto $protoInstance = null;
    private string $authKey = '';
    /** True when the live session reused cached key material (eligible for purge-recovery). */
    private bool $usedCachedKey = false;

    /**
     * @param int         $apiId   Application id from https://my.telegram.org/apps.
     * @param string      $apiHash Application hash.
     * @param bool        $isTest  Use the test DC map.
     * @param int         $dcId    Fallback DC id (storage/dc.txt wins if present).
     * @param int         $layer   Telegram API layer.
     * @param Storage|null $storage Runtime storage; defaults to <cwd>/database.
     * @param string|null $proxy   Proxy: a SOCKS5 URL ("socks5://…") or an
     *                             MTProxy link ("https://t.me/proxy?…",
     *                             "tg://proxy?…", "mtproxy://host:port?secret=…").
     *                             Falls back to the BPT_PROXY env var when null.
     * @param string|null $proxyUser SOCKS5 username (or BPT_PROXY_USER).
     * @param string|null $proxyPass SOCKS5 password (or BPT_PROXY_PASS).
     */
    public function __construct(
        private readonly int $apiId,
        private readonly string $apiHash,
        private readonly bool $isTest = false,
        int $dcId = 2,
        private readonly int $layer = Config::DEFAULT_LAYER,
        ?Storage $storage = null,
        private readonly ?string $proxy = null,
        private readonly ?string $proxyUser = null,
        private readonly ?string $proxyPass = null,
    ) {
        $this->storage = $storage ?? new Storage(getcwd() . '/database');
        $saved = $this->storage->get(self::KEY_DC);
        $this->dcId = ($saved !== null && $saved !== '') ? (int)trim($saved) : $dcId;
    }

    /**
     * Runtime storage (peer cache, auth state).
     */
    public function storage(): Storage
    {
        return $this->storage;
    }

    /**
     * The grouped facade bound to this client (created on first use).
     */
    public function proto(): BptProto
    {
        return $this->protoInstance ??= new BptProto($this);
    }

    /**
     * Current DC id (may change on PHONE_MIGRATE).
     */
    public function getDcId(): int
    {
        return $this->dcId;
    }

    /**
     * Force a different DC and drop the live connection.
     */
    public function setDcId(int $dcId): void
    {
        $this->disconnect();
        $this->dcId = $dcId;
        $this->storage->put(self::KEY_DC, (string)$dcId);
    }

    /**
     * Ensure a connected, authenticated session and return the raw 256-byte
     * auth key (running the DH handshake on first use / cache miss).
     */
    public function authKey(): string
    {
        $this->connect();
        return $this->authKey;
    }

    /**
     * 8-byte auth_key_id (SHA1(auth_key)[12:20]).
     */
    public function authKeyId(): string
    {
        return substr(sha1($this->authKey(), true), 12, 8);
    }

    /**
     * Connected low-level API hub (advanced use + wrappers).
     */
    public function api(): TelegramApi
    {
        $this->connect();
        /** @var TelegramApi $api */
        $api = $this->api;
        return $api;
    }

    /**
     * Realtime event loop (same object as {@see BptProto::$events}).
     */
    public function events(): Realtime
    {
        return $this->proto()->events;
    }

    /**
     * Subscribe to a realtime event. Shortcut for `$client->events()->on()`.
     *
     * @param callable|array|null $filter Exact-match array or `fn(array):bool`.
     */
    public function on(string $event, callable $handler, callable|array|null $filter = null): static
    {
        $this->events()->on($event, $handler, $filter);
        return $this;
    }

    /** Block and dispatch realtime events (0 = forever). */
    public function run(int $seconds = 0, int $interval = 1): void
    {
        $this->events()->run($seconds, $interval);
    }

    /** Ask a running {@see run()} loop to stop. */
    public function stop(): void
    {
        $this->events()->stop();
    }

    /**
     * Generic call for ANY method: pass a complete TL body.
     *
     * Same retry/recovery semantics as withRetry. Use with
     * {@see \Bpt\TL\Builder}.
     *
     * @throws FloodWaitException With $seconds to wait.
     * @throws AuthException On auth/session errors.
     */
    public function rpc(string $methodBody, int $retries = 3): string
    {
        return $this->withRetry(fn() => $this->api()->call($methodBody), $retries);
    }

    /**
     * help.getNearestDc — cheap proof that the encrypted channel works.
     *
     * @return array{country:string,nearest:int,this_dc:int}
     */
    public function getNearestDc(): array
    {
        $this->connect();
        return $this->api->getNearestDc();
    }

    /**
     * auth.sendCode — request an SMS/app login code, following DC migrations.
     *
     * The returned phone_code_hash (and the phone) is persisted, so a later
     * signIn() only needs the code.
     *
     * @return string phone_code_hash
     * @throws RpcErrorException On non-migration RPC errors (e.g. FLOOD_WAIT).
     */
    public function sendCode(string $phone): string
    {
        for ($attempt = 0; $attempt < 4; $attempt++) {
            $this->connect();
            try {
                $hash = $this->api->sendCode($phone, $this->apiHash);
                $this->storage->put(self::KEY_PHONE, $phone);
                $this->storage->put(self::KEY_CODE_HASH, $hash);
                return $hash;
            } catch (RpcErrorException $e) {
                if ($this->handleMigrate($e)) {
                    continue;
                }
                throw $e;
            }
        }
        throw new \RuntimeException('sendCode: too many DC migrations');
    }

    /**
     * auth.signIn — complete login with the received code.
     *
     * @param string      $phone          International format.
     * @param string      $code           Code received by SMS/app.
     * @param string|null $phoneCodeHash  Defaults to the one stored by sendCode().
     * @return string Raw auth.authorization object.
     * @throws RpcErrorException SESSION_PASSWORD_NEEDED when 2FA is enabled.
     */
    public function signIn(string $phone, string $code, ?string $phoneCodeHash = null): string
    {
        $hash = $phoneCodeHash ?? (string)$this->storage->get(self::KEY_CODE_HASH);
        if ($hash === '') {
            throw new \RuntimeException('call sendCode() first (no phone_code_hash stored)');
        }
        $this->connect();
        $auth = $this->api->signIn($phone, $hash, $code);
        $this->storeAuthResult($auth);
        return $auth;
    }

    /**
     * auth.checkPassword — finish login when 2FA (SRP) is enabled.
     *
     * @return string Raw auth.authorization object.
     */
    public function checkPassword(string $password): string
    {
        $this->connect();
        $auth = $this->api->checkPassword($password);
        $this->storeAuthResult($auth);
        return $auth;
    }

    /**
     * Full flow: sendCode, then signIn, transparently falling back to
     * checkPassword when 2FA is enabled and a password was supplied.
     *
     * @return string Raw auth.authorization object.
     */
    public function login(string $phone, string $code, ?string $password = null): string
    {
        $this->sendCode($phone);
        try {
            return $this->signIn($phone, $code);
        } catch (RpcErrorException $e) {
            if (str_contains($e->rpcType, 'SESSION_PASSWORD_NEEDED')) {
                if ($password === null) {
                    throw $e;
                }
                return $this->checkPassword($password);
            }
            throw $e;
        }
    }

    /**
     * phone_code_hash stored by the last sendCode(), if any.
     */
    public function codeHash(): ?string
    {
        $v = $this->storage->get(self::KEY_CODE_HASH);
        return $v === null ? null : $v;
    }

    /**
     * Phone number stored by the last sendCode(), if any.
     */
    public function phone(): ?string
    {
        $v = $this->storage->get(self::KEY_PHONE);
        return $v === null ? null : $v;
    }

    /**
     * Raw auth.authorization bytes from the last successful login, if any.
     */
    public function authResult(): ?string
    {
        return $this->storage->get(self::KEY_AUTH_RESULT);
    }

    /**
     * Persist a raw auth.authorization blob (used by login + DC transfer).
     */
    public function storeAuthResult(string $auth): void
    {
        $this->storage->put(self::KEY_AUTH_RESULT, $auth);
    }

    /**
     * Close the connection (idempotent).
     */
    public function close(): void
    {
        $this->disconnect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Connect (if needed), reusing the persisted auth key and session.
     *
     * Like MadelineProto: login once, the key + session live in storage
     * indefinitely. A handshake runs ONLY when no usable key is stored.
     */
    private function connect(): void
    {
        if ($this->transport !== null) {
            return;
        }
        $this->storage->acquireLock(30);
        try {
            $map = Config::dcMap($this->isTest);
            if (!isset($map[$this->dcId])) {
                throw new \RuntimeException("unknown DC {$this->dcId}");
            }

            $t = new AbridgedTransport(
                proxy: $this->proxy,
                proxyUser: $this->proxyUser,
                proxyPass: $this->proxyPass,
                dcId: $this->dcId,
            );
            $t->connectAny([$map[$this->dcId]], 443);
            $this->transport = $t;

            $akName = $this->authKeyName();
            $savedKey = (string)$this->storage->get($akName);
            if (strlen($savedKey) === 256) {
                // A persisted auth key is the ONLY thing needed to stay logged
                // in forever. The salt is just a hint — Telegram rotates it
                // (~30 min), so a stale/missing salt is recovered transparently
                // through new_session_created / bad_server_salt, never by
                // re-running the handshake (which would deauthorize the key).
                $this->authKey = $savedKey;
                $savedSalt = $this->storage->get($this->saltName());
                $salt = (is_string($savedSalt) && strlen($savedSalt) === 8)
                    ? $savedSalt
                    : "\0\0\0\0\0\0\0\0";
                $offset = (int)$this->storage->get($this->offsetName());
                $this->usedCachedKey = true;
            } else {
                $keys = RsaKeyStore::fromPemList(Config::rsaPems());
                $dcInner = $this->isTest ? 10000 + $this->dcId : $this->dcId;
                $r = (new AuthKeyExchange())->run($t, $keys, $dcInner, new MsgIdGenerator());
                $this->authKey = $r->authKey;
                $salt = $r->salt;
                $offset = $r->timeOffset;
                $this->storage->put($akName, $r->authKey);
                $this->storage->put($this->saltName(), $salt);
                $this->storage->put($this->offsetName(), (string)$offset);
                $this->usedCachedKey = false;
            }

            [$sessId, $seq, $lastMsg] = $this->loadSession();
            $msgIds = new MsgIdGenerator($offset, $lastMsg);
            $this->session = new EncryptedSession($t, $this->authKey, $salt, $msgIds, $sessId, $seq, function (): void {
                $this->persistSessionState();
            });
            $this->api = new TelegramApi($this->session, $this->apiId, $this->layer);
        } catch (\Throwable $e) {
            $this->transport?->close();
            $this->transport = null;
            $this->session = null;
            $this->api = null;
            $this->storage->releaseLock();
            throw $e;
        }
    }

    /**
     * Delete cached auth material for the current DC (forces a fresh
     * handshake on next connect). Used ONLY on explicit dead-key proof.
     */
    public function purgeDcAuth(): void
    {
        $this->disconnect();
        $this->storage->delete($this->authKeyName());
        $this->storage->delete($this->saltName());
        $this->storage->delete($this->offsetName());
        $this->storage->delete($this->sessionName());
    }

    /**
     * Drop the saved session (keep the auth key). The next connect starts
     * a fresh session — used when the server no longer knows ours.
     */
    public function dropSavedSession(): void
    {
        $this->disconnect();
        $this->storage->delete($this->sessionName());
    }

    /**
     * Drop the live socket/session (persisting session state, keeping keys).
     */
    private function disconnect(): void
    {
        if ($this->session !== null) {
            try {
                $this->persistSessionState();
            } catch (\Throwable) {
            }
        }
        $this->transport?->close();
        $this->transport = null;
        $this->session = null;
        $this->api = null;
        $this->storage->releaseLock();
    }

    /**
     * Persist the live session (id + seq + last msg_id) and the current salt.
     *
     * Called on disconnect AND whenever the server rotates the salt or issues
     * a new session (new_session_created / bad_server_salt), so a later process
     * resumes exactly where this one left off without re-login.
     */
    private function persistSessionState(): void
    {
        if ($this->session === null) {
            return;
        }
        $this->storage->put(
            $this->sessionName(),
            $this->session->sessionId()
            . pack('V', $this->session->seq())
            . \Bpt\Codec\TlCodec::packLong($this->session->lastMsgId())
        );
        $this->storage->put($this->saltName(), $this->session->salt());
    }

    /**
     * Run an API call with DC-migration retry + typed errors + dead-key recovery.
     *
     * Safety rules (learned the hard way):
     *  - transport timeout → ONE same-key reconnect+retry, then throw.
     *    The key is NEVER purged on a timeout: slow networks must not
     *    burn a good authorized key.
     *  - purge + fresh handshake happens ONLY on explicit proof
     *    (AUTH_KEY_UNREGISTERED and friends).
     * Note: a timed-out sendMessage may have landed server-side — callers
     * should check history before resending (at-least-once semantics).
     *
     * @internal Shared by every facade group.
     * @template T
     * @param callable():T $fn
     * @return T
     * @throws FloodWaitException With $seconds to wait.
     * @throws AuthException On auth/session errors.
     */
    public function withRetry(callable $fn, int $retries = 3): mixed
    {
        $purged = false;
        $retriedTimeout = false;
        $droppedSession = false;
        for ($attempt = 0; $attempt <= $retries; $attempt++) {
            try {
                return $fn();
            } catch (RpcErrorException $e) {
                if ($this->handleMigrate($e)) {
                    continue;
                }
                if (self::isDeadKey($e) && !$purged) {
                    $purged = true;
                    $this->purgeDcAuth();
                    continue;
                }
                if (($f = FloodWaitException::fromRpc($e)) !== null) {
                    throw $f;
                }
                if (($a = AuthException::fromRpc($e)) !== null) {
                    throw $a;
                }
                throw $e;
            } catch (\RuntimeException $e) {
                if ($e->getMessage() === 'disconnect/timeout' && !$retriedTimeout) {
                    $retriedTimeout = true;
                    $this->close(); // fresh socket, SAME key material
                    continue;
                }
                if ($e->getMessage() === 'no rpc_result in 12 packets' && !$droppedSession) {
                    $droppedSession = true;
                    $this->dropSavedSession(); // server forgot our session: start fresh
                    continue;
                }
                throw $e;
            }
        }
        throw new \RuntimeException('api: too many DC migrations');
    }

    private static function isDeadKey(RpcErrorException $e): bool
    {
        return in_array($e->rpcType, [
            'AUTH_KEY_UNREGISTERED', 'AUTH_KEY_INVALID', 'AUTH_KEY_PERM_EMPTY',
            'SESSION_REVOKED', 'SESSION_EXPIRED', 'USER_DEACTIVATED_BAN',
        ], true);
    }

    /**
     * Handle *_MIGRATE_x / *_TRANSFER_x by switching DC.
     *
     * Madeline-style: when the current DC is still authorized, the login is
     * carried over with auth.exportAuthorization → auth.importAuthorization,
     * so no SMS re-login is needed (FILE/NETWORK/USER_MIGRATE on media or
     * channels, or an explicit move). When there is nothing to carry
     * (fresh login flow, dead source key) it falls back to a plain switch
     * and the caller continues its own flow (e.g. sendCode on the new DC).
     *
     * @return bool True when the error was a migration and a retry is useful.
     */
    private function handleMigrate(RpcErrorException $e): bool
    {
        if (!preg_match('/_(MIGRATE|TRANSFER)_(\d+)$/', $e->rpcType, $m)) {
            return false;
        }
        $newDc = (int)$m[2];
        $exported = null;
        if ($this->api !== null) {
            try {
                $exported = $this->api->exportAuthorization($newDc);
            } catch (\Throwable) {
                $exported = null;
            }
        }
        $this->setDcId($newDc);
        if ($exported !== null) {
            try {
                $this->connect();
                $auth = $this->api->importAuthorization($exported['id'], $exported['bytes']);
                $this->storeAuthResult($auth);
            } catch (\Throwable) {
                // Import failed: the retried call will surface the real state.
            }
        }
        return true;
    }

    private function authKeyName(): string
    {
        return "authkey_dc{$this->dcId}.bin";
    }

    /**
     * Load the persisted session (session_id, seq, last msg_id).
     *
     * @return array{0:string|null,1:int,2:int}
     */
    private function loadSession(): array
    {
        $raw = $this->storage->get($this->sessionName());
        if ($raw === null || strlen($raw) !== 20) {
            return [null, 0, 0];
        }
        $sessId = substr($raw, 0, 8);
        $seq = \Bpt\Codec\TlCodec::unpackInt($raw, 8);
        $r = new \Bpt\TL\Reader(substr($raw, 12, 8));
        return [$sessId, $seq, $r->long()];
    }

    private function saltName(): string
    {
        return "salt_dc{$this->dcId}.bin";
    }

    private function offsetName(): string
    {
        return "offset_dc{$this->dcId}.txt";
    }

    private function sessionName(): string
    {
        return "session_dc{$this->dcId}.bin";
    }
}
