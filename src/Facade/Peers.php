<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\Codec\TlCodec;
use Bpt\Entity\Message;
use Bpt\Entity\User;
use Bpt\TL\Builder;
use Bpt\TL\Ctors;
use Bpt\TL\Deserializer;
use Bpt\TL\Peer;
use Bpt\TL\Reader;

/**
 * Peer resolution + the opportunistic peer cache. → `$proto->peers`
 *
 * Every method that takes a destination delegates here, so `resolvePeer()`
 * accepts the same friendly forms everywhere:
 *  - 'me' | 'self' | null            → Saved Messages
 *  - '@username' | 'username'         → resolveUsername (cached)
 *  - ['user'=>id,'access_hash'=>h]    → user
 *  - ['channel'=>id,'access_hash'=>h] → channel/supergroup
 *  - ['chat'=>id]                     → basic group (no hash needed)
 *  - packed TL bytes                  → passed through
 *  - int id                           → peer-cache lookup (must be unique)
 */
final class Peers extends Group
{
    private const KEY_PEERS = 'peers.json';

    /** @var array{users:array<string,array>,channels:array<string,array>}|null */
    private ?array $peerCache = null;

    private int $lastResolvedId = 0;
    private int $lastResolvedHash = 0;
    private string $lastResolvedType = '';

    public function resolvePeer(mixed $peer): string
    {
        if (is_string($peer) && self::looksPackedPeer($peer)) {
            return $peer;
        }
        if (Peer::isSelf($peer)) {
            return Peer::self();
        }
        if (is_array($peer)) {
            if (isset($peer['user'])) {
                return Peer::user((int)$peer['user'], (int)($peer['access_hash'] ?? 0));
            }
            if (isset($peer['channel'])) {
                return Peer::channel((int)$peer['channel'], (int)($peer['access_hash'] ?? 0));
            }
            if (isset($peer['chat'])) {
                return Peer::chat((int)$peer['chat']);
            }
            throw new \InvalidArgumentException('peer array needs user|channel|chat key');
        }
        if (is_string($peer) && ($typed = self::parseTypedPeer($peer)) !== null) {
            [$type, $id] = $typed;
            return match ($type) {
                'user' => $this->resolveUserPeer($id),
                'chat' => Peer::chat($id),
                'channel' => $this->resolveChannelPeer($id),
            };
        }
        if (is_int($peer) || (is_string($peer) && ctype_digit(trim($peer)))) {
            return $this->resolvePeerFromCache((int)$peer);
        }
        if (Peer::isUsername($peer)) {
            return $this->resolveInputPeerByUsername(Peer::normUsername((string)$peer));
        }
        throw new \InvalidArgumentException('cannot resolve peer: ' . var_export($peer, true));
    }

    /**
     * Resolve a channel to a packed InputChannel blob (for channels.*).
     */
    public function resolveChannel(mixed $channel): string
    {
        if (is_string($channel) && self::looksPackedChannel($channel)) {
            return $channel;
        }
        if (is_array($channel) && isset($channel['channel'])) {
            return Peer::inputChannel((int)$channel['channel'], (int)($channel['access_hash'] ?? 0));
        }
        if (Peer::isUsername($channel)) {
            $this->resolveInputPeerByUsername(Peer::normUsername((string)$channel));
            $id = $this->lastResolvedId;
            $hash = $this->lastResolvedHash;
            if ($this->lastResolvedType !== 'channel') {
                throw new \RuntimeException("@$channel is not a channel");
            }
            return Peer::inputChannel($id, $hash);
        }
        if (is_int($channel) || (is_string($channel) && ctype_digit(trim($channel)))) {
            $e = $this->findCachedChannel((int)$channel);
            return Peer::inputChannel((int)$channel, (int)$e['access_hash']);
        }
        throw new \InvalidArgumentException('cannot resolve channel: ' . var_export($channel, true));
    }

    /**
     * contacts.resolveUsername → packed InputPeer + cached access_hash.
     *
     * @return array{type:string,id:int,username:string}
     */
    public function resolveUsername(string $username): array
    {
        $username = Peer::normUsername($username);
        $this->resolveInputPeerByUsername($username);
        return ['type' => $this->lastResolvedType, 'id' => $this->lastResolvedId, 'username' => $username];
    }

    /**
     * Resolve an InputUser (for users.*, channels.* admin calls, …).
     *
     * Accepts 'me'/'self'/null (inputUserSelf), ['user'=>id,'access_hash'=>h],
     * a bare int id, or an already-packed InputUser blob.
     */
    public function resolveUser(mixed $user): string
    {
        if (is_string($user) && self::looksPackedUser($user)) {
            return $user;
        }
        if (Peer::isSelf($user)) {
            return Peer::inputUserSelf();
        }
        if (is_array($user) && isset($user['user'])) {
            return Peer::inputUser((int)$user['user'], (int)($user['access_hash'] ?? 0));
        }
        if (is_int($user) || (is_string($user) && ctype_digit(trim($user)))) {
            return Peer::inputUser((int)$user, 0);
        }
        throw new \InvalidArgumentException('cannot resolve user: ' . var_export($user, true));
    }

    /**
     * Resolve an InputDialogPeer ('me', peer forms, or a packed blob).
     */
    public function resolveDialogPeer(mixed $peer): string
    {
        if (is_string($peer) && self::looksPackedDialogPeer($peer)) {
            return $peer;
        }
        if (Peer::isSelf($peer)) {
            return Peer::inputDialogPeer(Peer::self());
        }
        return Peer::inputDialogPeer($this->resolvePeer($peer));
    }

    /**
     * Resolve a user id to a packed InputPeerUser, fetching + caching the
     * access_hash when it is not known yet (e.g. a reply to an incoming
     * updateShortMessage that carried no entity table).
     */
    private function resolveUserPeer(int $id): string
    {
        $cache = $this->loadPeerCache();
        $cached = $cache['users'][(string)$id]['access_hash'] ?? null;
        if ($cached !== null && (int)$cached !== 0) {
            return Peer::user($id, (int)$cached);
        }
        try {
            $fetched = $this->fetchUsersById([$id]);
            if (isset($fetched[(string)$id])) {
                $this->rememberUserArray($fetched[(string)$id]);
                return Peer::user($id, (int)($fetched[(string)$id]['access_hash'] ?? 0));
            }
        } catch (\Throwable) {
        }
        if ($cached !== null) {
            return Peer::user($id, (int)$cached);
        }
        throw new \RuntimeException("unknown user $id: pass ['user'=>$id,'access_hash'=>h] or @username");
    }

    /**
     * Resolve a channel id to a packed InputPeerChannel (cache first, then
     * channels.getChannels with a zero hash as a best-effort fallback).
     */
    private function resolveChannelPeer(int $id): string
    {
        $cache = $this->loadPeerCache();
        if (isset($cache['channels'][(string)$id])) {
            return Peer::channel($id, (int)$cache['channels'][(string)$id]['access_hash']);
        }
        try {
            $body = Builder::ctor(0x0A7F6BBB) // channels.getChannels
                ->vector([Peer::inputChannel($id, 0)])
                ->build();
            $parsed = Deserializer::parse($this->client->rpc($body), 'messages.Chats');
            foreach (($parsed['chats'] ?? []) as $c) {
                if (is_array($c) && (int)($c['id'] ?? 0) === $id && isset($c['access_hash'])) {
                    $this->rememberChannel($id, (int)$c['access_hash'], (string)($c['username'] ?? ''));
                    return Peer::channel($id, (int)$c['access_hash']);
                }
            }
        } catch (\Throwable) {
        }
        throw new \RuntimeException("unknown channel $id: pass ['channel'=>$id,'access_hash'=>h] or @username");
    }

    /**
     * users.getUsers for a set of ids (access_hash=0 works for known peers).
     *
     * @param int[] $ids
     * @return array<string,array> id => parsed user
     */
    private function fetchUsersById(array $ids): array
    {
        $blobs = [];
        foreach ($ids as $id) {
            $blobs[] = Peer::inputUser((int)$id, 0);
        }
        $body = Builder::ctor(0xD91A548)->vector($blobs)->build(); // users.getUsers
        $list = Deserializer::parse($this->client->rpc($body), 'Vector<User>');
        $out = [];
        foreach ((array)$list as $u) {
            if (is_array($u) && !empty($u['id'])) {
                $out[(string)$u['id']] = $u;
            }
        }
        return $out;
    }

    /**
     * Parse a "type:id" peer string (`user:123`, `chat:123`, `channel:123`).
     *
     * @return array{0:string,1:int}|null
     */
    private static function parseTypedPeer(string $peer): ?array
    {
        if (preg_match('/^(user|chat|channel)\s*:\s*(-?\d+)$/i', trim($peer), $m) === 1) {
            return [strtolower($m[1]), (int)$m[2]];
        }
        return null;
    }

    // --------------------------------------------------------------- cache

    /** @internal Remember a user (caller already has a parsed entity). */
    public function rememberUser(User $u): void
    {
        $this->rememberUserArray($u->toArray());
    }

    /** @internal Remember a user given its array form. */
    public function rememberUserArray(array $u): void
    {
        if (empty($u['id'])) {
            return;
        }
        $cache = $this->loadPeerCache();
        $id = (string)$u['id'];
        $prev = $cache['users'][$id] ?? [];
        $cache['users'][$id] = [
            'access_hash' => $u['access_hash'] ?? $prev['access_hash'] ?? 0,
            'username' => $u['username'] ?? $prev['username'] ?? '',
            'first_name' => $u['first_name'] ?? $prev['first_name'] ?? '',
        ];
        $this->peerCache = $cache;
        $this->savePeerCache();
    }

    /** @internal Remember a channel by id + access_hash. */
    public function rememberChannel(int $id, int $hash, string $username = ''): void
    {
        $cache = $this->loadPeerCache();
        $prev = $cache['channels'][(string)$id] ?? [];
        $cache['channels'][(string)$id] = [
            'access_hash' => $hash,
            'username' => $username !== '' ? $username : ($prev['username'] ?? ''),
        ];
        $this->peerCache = $cache;
        $this->savePeerCache();
    }

    // ----------------------------------------------------------- internals

    private static function looksPackedPeer(string $b): bool
    {
        if (strlen($b) < 4) {
            return false;
        }
        $ctor = TlCodec::unpackInt($b, 0);
        return in_array($ctor, [0x7DA07EC9, 0x7F3B18EA, 0xDDE8A54C, 0x35A95CB9, 0x27BCBBFC], true);
    }

    private static function looksPackedChannel(string $b): bool
    {
        return strlen($b) >= 4 && TlCodec::unpackInt($b, 0) === 0xF35AEC28;
    }

    private static function looksPackedUser(string $b): bool
    {
        return strlen($b) >= 4 && in_array(TlCodec::unpackInt($b, 0), [0xF7C1B13F, 0xB98886CF, 0xF21158C6], true);
    }

    private static function looksPackedDialogPeer(string $b): bool
    {
        return strlen($b) >= 4 && TlCodec::unpackInt($b, 0) === 0xFCAAFEB7;
    }

    private function resolvePeerFromCache(int $id): string
    {
        $cache = $this->loadPeerCache();
        $found = [];
        foreach (['users' => 'user', 'channels' => 'channel'] as $section => $kind) {
            if (isset($cache[$section][(string)$id])) {
                $e = $cache[$section][(string)$id];
                $found[] = [$kind, $e];
            }
        }
        if (count($found) === 1) {
            [$kind, $e] = $found[0];
            return $kind === 'user'
                ? Peer::user($id, (int)$e['access_hash'])
                : Peer::channel($id, (int)$e['access_hash']);
        }
        if ($found === []) {
            throw new \RuntimeException("unknown id $id: pass ['user'=>id,'access_hash'=>h], ['channel'=>...], ['chat'=>id] or @username");
        }
        throw new \RuntimeException("ambiguous id $id: specify user|channel|chat explicitly");
    }

    private function findCachedChannel(int $id): array
    {
        $cache = $this->loadPeerCache();
        if (isset($cache['channels'][(string)$id])) {
            return $cache['channels'][(string)$id];
        }
        throw new \RuntimeException("unknown channel $id: pass ['channel'=>id,'access_hash'=>h] or @username first");
    }

    /**
     * Resolve @username → packed InputPeer (user/channel/chat), caching hashes.
     */
    private function resolveInputPeerByUsername(string $username): string
    {
        $cache = $this->loadPeerCache();
        foreach (['users', 'channels'] as $section) {
            foreach ($cache[$section] as $id => $e) {
                if (strcasecmp((string)($e['username'] ?? ''), $username) === 0) {
                    $this->lastResolvedId = (int)$id;
                    $this->lastResolvedHash = (int)$e['access_hash'];
                    $this->lastResolvedType = $section === 'users' ? 'user' : 'channel';
                    return $section === 'users'
                        ? Peer::user((int)$id, (int)$e['access_hash'])
                        : Peer::channel((int)$id, (int)$e['access_hash']);
                }
            }
        }
        $raw = $this->client->withRetry(fn() => $this->client->api()->contacts()->resolveUsername($username));
        $r = Reader::of($raw);
        if ($r->ctor() !== 0x7F077AD9) { // resolvedPeer
            throw new \RuntimeException('resolveUsername: unexpected response');
        }
        [$pt, $pid] = Message::readPeer($r);
        if ($pt === 'chat') {
            $this->lastResolvedId = $pid;
            $this->lastResolvedHash = 0;
            $this->lastResolvedType = 'chat';
            return Peer::chat($pid);
        }
        if ($pt === 'channel') {
            // chats Vector<Chat> first element holds access_hash in its prefix.
            // channel = ctor, flags, flags2, id, access_hash:flags.13?long, ...
            // (legacy layers lack flags2 → disambiguate via the peer id).
            $n = $r->vectorHeader();
            if ($n < 1) {
                throw new \RuntimeException('resolveUsername: empty chats');
            }
            $ctor = $r->ctor();
            if ($ctor !== Ctors::CHANNEL) {
                throw new \RuntimeException('resolveUsername: not a channel (0x' . dechex($ctor) . ')');
            }
            $flags = $r->int();
            $tail = substr($r->raw(), $r->offset());
            $peek = static fn(int $off): int => Reader::of(substr($tail, $off))->long();
            if ($peek(4) === $pid) {
                $r->int();
                $id = $r->long(); // current shape
            } elseif ($peek(0) === $pid) {
                $id = $r->long(); // legacy shape (no flags2)
            } else {
                throw new \RuntimeException('resolveUsername: channel id mismatch');
            }
            $hash = ($flags & (1 << 13)) ? $r->long() : 0;
            $this->rememberChannel($id, $hash, $username);
            $this->lastResolvedId = $id;
            $this->lastResolvedHash = $hash;
            $this->lastResolvedType = 'channel';
            return Peer::channel($id, $hash);
        }
        // user: chats vector should be empty, users vector carries the hash
        $n = $r->vectorHeader();
        if ($n !== 0) {
            throw new \RuntimeException('resolveUsername: unexpected chats for user');
        }
        $users = User::parseVector($r);
        foreach ($users as $u) {
            $this->rememberUser($u);
            if ($u->id === $pid || strcasecmp($u->username, $username) === 0) {
                $this->lastResolvedId = $u->id;
                $this->lastResolvedHash = $u->accessHash;
                $this->lastResolvedType = 'user';
                return Peer::user($u->id, $u->accessHash);
            }
        }
        throw new \RuntimeException("resolveUsername: @$username not in response");
    }

    /** @return array{users:array<string,array>,channels:array<string,array>} */
    private function loadPeerCache(): array
    {
        if ($this->peerCache !== null) {
            return $this->peerCache;
        }
        $raw = $this->client->storage()->get(self::KEY_PEERS);
        $data = $raw !== null && $raw !== '' ? json_decode($raw, true) : null;
        if (!is_array($data)) {
            $data = ['users' => [], 'channels' => []];
        }
        $data += ['users' => [], 'channels' => []];
        return $this->peerCache = $data;
    }

    private function savePeerCache(): void
    {
        $this->client->storage()->put(
            self::KEY_PEERS,
            (string)json_encode($this->peerCache ?? ['users' => [], 'channels' => []])
        );
    }
}
