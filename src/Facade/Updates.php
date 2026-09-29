<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\Entity\Updates as UpdateEntity;
use Bpt\Entity\User;
use Bpt\Facade\Concerns\ParsesMessages;
use Bpt\TL\Reader;

/**
 * Realtime events (Madeline-style). → `$proto->updates`
 *
 * For push-style handling pass a callback to listen():
 *
 *   $proto->updates->listen(function (array $u): void {
 *       if ($u['kind'] === 'new_message') echo $u['text'], "\n";
 *   });
 */
final class Updates extends Group
{
    use ParsesMessages;

    /**
     * Drain realtime updates seen on this connection.
     *
     * Telegram piggybacks updates on the next RPC reply, so a cheap
     * trigger call (updates.getState) is issued first to flush them.
     *
     * @return array[] Each: ['kind'=>'new_message', 'text'=>..., ...]
     */
    public function pollUpdates(bool $trigger = true): array
    {
        if ($trigger) {
            try {
                $this->client->withRetry(fn() => $this->client->api()->meta()->getState());
            } catch (\Throwable) {
            }
        }
        $raws = $this->pollUpdatesRaw();
        $out = [];
        foreach ($raws as $raw) {
            try {
                foreach (UpdateEntity::parse(Reader::of($raw)) as $u) {
                    $this->cacheUpdatePeer($u);
                    $out[] = $u;
                }
            } catch (\Throwable) {
            }
        }
        return $out;
    }

    /** Raw pending Updates blobs (no trigger call, no parsing). */
    public function pollUpdatesRaw(): array
    {
        try {
            return $this->client->api()->drainPendingUpdates();
        } catch (\Throwable) {
            return [];
        }
    }

    /** Mark yourself online (so pushes arrive faster). Returns raw Bool. */
    public function setOnline(bool $online = true): bool
    {
        $raw = $this->client->withRetry(fn() => $this->client->api()->meta()->setOnline($online));
        try {
            return Reader::of($raw)->bool();
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * updates.getDifference → catch-up after updatesTooLong / gaps.
     *
     * @return array{messages:array[],updates:array[],users:array[],state:array}
     */
    public function getDifference(int $pts, int $date, int $qts): array
    {
        $raw = $this->client->withRetry(fn() => $this->client->api()->meta()->getDifference($pts, $date, $qts));
        $r = Reader::of($raw);
        $ctor = $r->ctor();
        if ($ctor === 0x5E2AD36E) { // differenceEmpty
            return [
                'messages' => [], 'updates' => [], 'users' => [],
                'state' => ['pts' => $r->int(), 'qts' => $r->int(), 'date' => $r->int(), 'seq' => $r->int()],
                'empty' => true,
            ];
        }
        if ($ctor === 0x060F599A) { // differenceTooLong
            return ['messages' => [], 'updates' => [['kind' => 'too_long']],
                'users' => [], 'state' => ['pts' => $r->int()], 'too_long' => true];
        }
        if ($ctor !== 0xC9845D43 && $ctor !== 0xA8FB1981) { // difference / differenceSlice
            throw new \RuntimeException('getDifference: unexpected 0x' . dechex($ctor));
        }
        $messages = self::parseMessageVectorBestEffort($r);
        // other_updates: Vector<Update>
        $updates = [];
        try {
            $n = $r->vectorHeader();
            for ($i = 0; $i < $n; $i++) {
                try {
                    $u = UpdateEntity::parseOneUpdate($r);
                    if ($u !== null) {
                        $updates[] = $u;
                    }
                } catch (\Throwable) {
                    break;
                }
            }
        } catch (\Throwable) {
        }
        $users = [];
        try {
            $users = array_map(static fn(User $u) => $u->toArray(), User::parseVector($r));
        } catch (\Throwable) {
        }
        $state = [];
        try {
            // intermediate_state: pts, ... best-effort
            $r->ctor();
            $state = ['pts' => $r->int(), 'qts' => $r->int(), 'date' => $r->int(), 'seq' => $r->int()];
        } catch (\Throwable) {
        }
        return ['messages' => $messages, 'updates' => $updates, 'users' => $users, 'state' => $state];
    }

    /** updates.getState → pts/qts/date/seq. */
    public function getState(): array
    {
        $raw = $this->client->withRetry(fn() => $this->client->api()->meta()->getState());
        $r = Reader::of($raw);
        $r->ctor();
        return ['pts' => $r->int(), 'qts' => $r->int(), 'date' => $r->int(), 'seq' => $r->int(), 'unread' => $r->int()];
    }

    /**
     * Poll loop. $seconds=0 runs forever.
     *
     * @param callable(array):void|null $onUpdate
     */
    public function listen(?callable $onUpdate = null, int $seconds = 0, int $interval = 2): void
    {
        $start = time();
        try {
            $this->setOnline(true);
        } catch (\Throwable) {
        }
        while (true) {
            foreach ($this->pollUpdates(true) as $u) {
                if ($onUpdate !== null) {
                    $onUpdate($u);
                }
            }
            if ($seconds > 0 && time() - $start >= $seconds) {
                return;
            }
            sleep(max(1, $interval));
        }
    }

    private function cacheUpdatePeer(array $u): void
    {
        try {
            foreach ($u['users'] ?? [] as $usr) {
                if (is_array($usr)) {
                    $this->peers()->rememberUserArray($usr);
                }
            }
        } catch (\Throwable) {
        }
        try {
            foreach ($u['chats'] ?? [] as $chat) {
                if (!is_array($chat)) {
                    continue;
                }
                $kind = (string)($chat['_'] ?? '');
                if (($kind === 'channel' || $kind === 'channelForbidden')
                    && !empty($chat['id']) && isset($chat['access_hash'])
                ) {
                    $this->peers()->rememberChannel(
                        (int)$chat['id'],
                        (int)$chat['access_hash'],
                        (string)($chat['username'] ?? ''),
                    );
                }
            }
        } catch (\Throwable) {
        }
    }
}
