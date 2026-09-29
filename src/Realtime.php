<?php

declare(strict_types=1);

namespace Bpt;

use Bpt\Exception\AuthException;
use Bpt\Exception\FloodWaitException;
use Bpt\Facade\Updates;
use Bpt\Session\RpcErrorException;

/**
 * Tiny event loop for realtime apps — no handler class required.
 *
 * ```php
 * $tg = BptProto::create(apiId: 123456, apiHash: '…');
 *
 * $tg->on('message', function (array $m) use ($tg) {
 *     if (!$m['out']) {
 *         $tg->messages->sendMessage($m['peer_type'] . ':' . $m['peer_id'], 'echo: ' . $m['text']);
 *     }
 * });
 * $tg->run();          // blocks, reconnects on errors; Ctrl+C to stop
 * ```
 *
 * Event names (first match wins on registration, every matching listener runs):
 *  - `message`  new incoming/outgoing message (`new_message`)
 *  - `edited`   message edited (`edit_message`)
 *  - `deleted`  messages deleted (`delete_messages`)
 *  - `sent`     your outgoing echo (`sent`)
 *  - `too_long` update gap — call updates->getDifference()
 *  - `update` / `*`  every update
 *  - ANY Telegram update constructor, e.g. `updateUserStatus`,
 *    `updateReadHistoryInbox`, `updateNewMessage`, … (all 169 are parsed)
 *  - `start` / `stop`  loop lifecycle
 *
 * Every dispatched update carries `users` and `chats` side tables (when the
 * server sent them) so handlers can resolve senders/chats by id:
 *
 * ```php
 * $tg->on('updateUserStatus', function (array $u) use ($tg) {
 *     $userId = $u['user_id'];
 *     // $u['users'] / $u['chats'] available for lookup
 * }, fn(array $u) => ($u['status']['_'] ?? '') === 'userStatusOnline');
 * ```
 *
 * An optional filter narrows a listener: an array of exact field matches
 * (e.g. `['out' => false, 'peer_type' => 'user']`) or a `fn(array $u): bool`.
 */
final class Realtime
{
    /** @var array<int,array{event:string,handler:callable,filter:callable|array|null}> */
    private array $listeners = [];

    private bool $stopped = false;
    private ?\Throwable $lastError = null;

    /** @var callable|null */
    private $onError = null;

    /** @var callable|null */
    private $onAuthError = null;

    private int $backoff = 1;

    /** Telegram update kind → friendly event name. */
    private const KINDS = [
        'new_message' => 'message',
        'edit_message' => 'edited',
        'delete_messages' => 'deleted',
        'too_long' => 'too_long',
        'sent' => 'sent',
    ];

    /**
     * @param callable():array[]|null $poll Advanced/test seam: replaces the
     *   live poll. Return a batch of normalized updates, or throw to exercise
     *   recovery. When null (the normal case) the live session is polled.
     */
    public function __construct(
        private readonly Client $client,
        private $poll = null,
    ) {
    }

    /**
     * Subscribe to an event.
     *
     * @param callable|array|null $filter Exact-match array or `fn(array):bool`.
     */
    public function on(string $event, callable $handler, callable|array|null $filter = null): self
    {
        $this->listeners[] = ['event' => $event, 'handler' => $handler, 'filter' => $filter];
        return $this;
    }

    /** Called for every handled failure (FloodWait, auth, network). */
    public function onError(callable $handler): self
    {
        $this->onError = $handler;
        return $this;
    }

    /**
     * Called when the session is dead. Perform the login here; if you do not
     * subscribe, the loop stops on an auth error instead of spinning.
     */
    public function onAuthError(callable $handler): self
    {
        $this->onAuthError = $handler;
        return $this;
    }

    /** Ask the running loop to stop after the current iteration. */
    public function stop(): void
    {
        $this->stopped = true;
    }

    /** The last failure seen by {@see run()}, if any. */
    public function lastError(): ?\Throwable
    {
        return $this->lastError;
    }

    /**
     * Block and dispatch updates until stop()/deadline.
     *
     * @param int $seconds 0 = forever, else stop after N seconds.
     * @param int $interval Seconds to pause between empty polls.
     */
    public function run(int $seconds = 0, int $interval = 1): void
    {
        $this->stopped = false;
        $this->backoff = 1;
        $deadline = $seconds > 0 ? time() + $seconds : 0;

        if ($this->poll === null) {
            try {
                $this->updates()->setOnline(true);
            } catch (\Throwable $e) {
                $this->fail($e);
            }
        }
        $this->emit('start', ['kind' => 'start']);

        while (!$this->stopped) {
            if ($deadline !== 0 && time() >= $deadline) {
                break;
            }
            try {
                $batch = $this->fetch();
                $this->backoff = 1;
                foreach ($batch as $u) {
                    $this->dispatch($u);
                }
            } catch (FloodWaitException $e) {
                $this->fail($e);
                $this->pause(min(max($e->seconds, 1), 60));
                continue;
            } catch (\Throwable $e) {
                $this->fail($e);
                if (!$this->recover($e)) {
                    break;
                }
                $this->pause($this->backoff);
                continue;
            }
            $this->pause($interval);
        }

        $this->emit('stop', ['kind' => 'stop']);
    }

    /**
     * Recover from a failure. Returns false when the loop must end.
     */
    private function recover(\Throwable $e): bool
    {
        $auth = $e instanceof AuthException
            || ($e instanceof RpcErrorException && AuthException::isAuthError($e->rpcType));

        if ($auth) {
            if ($this->onAuthError === null) {
                return false;
            }
            try {
                ($this->onAuthError)($e);
                return true;
            } catch (\Throwable $loginError) {
                $this->fail($loginError);
                return false;
            }
        }

        // Network / protocol hiccup: drop the socket so the next poll
        // reconnects with the same key, and back off a little.
        $this->client->close();
        $this->backoff = min($this->backoff * 2, 30);
        return true;
    }

    private function dispatch(array $update): void
    {
        $names = $this->names($update);
        foreach ($this->listeners as $listener) {
            if (!in_array($listener['event'], $names, true)) {
                continue;
            }
            if (!$this->passes($listener['filter'], $update)) {
                continue;
            }
            try {
                ($listener['handler'])($update);
            } catch (\Throwable $e) {
                $this->fail($e);
            }
        }
    }

    /**
     * Deliver a lifecycle event only to explicit listeners — never to the
     * `update`/`*` catch-alls, which are for real Telegram updates.
     */
    private function emit(string $event, array $payload): void
    {
        foreach ($this->listeners as $listener) {
            if ($listener['event'] !== $event) {
                continue;
            }
            if (!$this->passes($listener['filter'], $payload)) {
                continue;
            }
            try {
                ($listener['handler'])($payload);
            } catch (\Throwable $e) {
                $this->fail($e);
            }
        }
    }

    /** @return string[] */
    private function names(array $update): array
    {
        $kind = (string)($update['kind'] ?? 'update');
        $names = [self::KINDS[$kind] ?? $kind, $kind];

        // Allow subscribing by the raw update constructor name.
        $ctor = $update['_'] ?? null;
        if (is_string($ctor) && $ctor !== '') {
            $names[] = $ctor;
        }
        $names[] = 'update';
        $names[] = '*';

        return array_values(array_unique($names));
    }

    private function passes(callable|array|null $filter, array $update): bool
    {
        if ($filter === null) {
            return true;
        }
        if (is_array($filter)) {
            foreach ($filter as $key => $value) {
                if (!array_key_exists($key, $update) || $update[$key] !== $value) {
                    return false;
                }
            }
            return true;
        }
        return (bool)$filter($update);
    }

    private function fail(\Throwable $e): void
    {
        $this->lastError = $e;
        if ($this->onError !== null) {
            try {
                ($this->onError)($e);
            } catch (\Throwable) {
            }
        }
    }

    /** Sleep, but wake early when stop() is called. */
    private function pause(int $seconds): void
    {
        $until = microtime(true) + max(0, $seconds);
        while (!$this->stopped && microtime(true) < $until) {
            usleep(100_000);
        }
    }

    /**
     * One poll: the injected source, or the live session.
     *
     * A visible call both triggers the flush and surfaces a dead session;
     * the actual updates are drained right after.
     *
     * @return array[]
     */
    private function fetch(): array
    {
        if ($this->poll !== null) {
            return ($this->poll)();
        }
        $this->updates()->getState();
        return $this->updates()->pollUpdates(false);
    }

    private function updates(): Updates
    {
        return $this->client->proto()->updates;
    }
}
