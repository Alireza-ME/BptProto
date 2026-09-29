<?php

declare(strict_types=1);

namespace Bpt;

use Bpt\Api\TelegramApi;
use Bpt\Facade\Account;
use Bpt\Facade\Aicompose;
use Bpt\Facade\Auth;
use Bpt\Facade\Bots;
use Bpt\Facade\Channels;
use Bpt\Facade\Chatlists;
use Bpt\Facade\Communities;
use Bpt\Facade\Contacts;
use Bpt\Facade\Ephemeral;
use Bpt\Facade\Files;
use Bpt\Facade\Folders;
use Bpt\Facade\Fragment;
use Bpt\Facade\Help;
use Bpt\Facade\Langpack;
use Bpt\Facade\Messages;
use Bpt\Facade\Payments;
use Bpt\Facade\Peers;
use Bpt\Facade\Phone;
use Bpt\Facade\Photos;
use Bpt\Facade\Premium;
use Bpt\Facade\Session;
use Bpt\Facade\Smsjobs;
use Bpt\Facade\Stats;
use Bpt\Facade\Stickers;
use Bpt\Facade\Stories;
use Bpt\Facade\Updates;
use Bpt\Facade\Upload;
use Bpt\Facade\Users;

/**
 * Grouped facade over {@see Client} — the recommended entry point.
 *
 * ```php
 * $proto = BptProto::create(apiId: 123456, apiHash: '…');
 * $proto->client->login('+989120000000', '12345');
 * $me = $proto->users->getUsers(['me'])[0];
 * $proto->messages->sendMessage('me', 'hello saved messages', random_int(1, PHP_INT_MAX));
 * $proto->messages->sendMessage('@username', 'hi', random_int(1, PHP_INT_MAX));
 * foreach ($proto->updates->pollUpdates() as $u) { … }
 * $proto->session->close();
 * ```
 *
 * Every Telegram namespace is a group (auto-generated from telegram_api.tl,
 * see tools/gen_methods.php) plus a few hand-written ones for library-specific
 * concerns: peers (resolution), session (lifecycle), updates (realtime),
 * files (chunked upload/download). Login flow lives on {@see Client}.
 */
class BptProto
{
    public readonly Auth $auth;
    public readonly Session $session;
    public readonly Users $users;
    public readonly Messages $messages;
    public readonly Contacts $contacts;
    public readonly Channels $channels;
    public readonly Peers $peers;
    public readonly Updates $updates;
    public readonly Files $files;
    public readonly Account $account;
    public readonly Bots $bots;
    public readonly Payments $payments;
    public readonly Phone $phone;
    public readonly Photos $photos;
    public readonly Stickers $stickers;
    public readonly Stories $stories;
    public readonly Premium $premium;
    public readonly Chatlists $chatlists;
    public readonly Langpack $langpack;
    public readonly Smsjobs $smsjobs;
    public readonly Fragment $fragment;
    public readonly Ephemeral $ephemeral;
    public readonly Aicompose $aicompose;
    public readonly Communities $communities;
    public readonly Folders $folders;
    public readonly Stats $stats;
    public readonly Help $help;
    public readonly Upload $upload;

    /** Event loop for realtime apps (`on()` / `run()` / `stop()`). */
    public readonly Realtime $events;

    public function __construct(public readonly Client $client)
    {
        $this->auth = new Auth($client);
        $this->session = new Session($client);
        $this->users = new Users($client);
        $this->messages = new Messages($client);
        $this->contacts = new Contacts($client);
        $this->channels = new Channels($client);
        $this->peers = new Peers($client);
        $this->updates = new Updates($client);
        $this->files = new Files($client);
        $this->account = new Account($client);
        $this->bots = new Bots($client);
        $this->payments = new Payments($client);
        $this->phone = new Phone($client);
        $this->photos = new Photos($client);
        $this->stickers = new Stickers($client);
        $this->stories = new Stories($client);
        $this->premium = new Premium($client);
        $this->chatlists = new Chatlists($client);
        $this->langpack = new Langpack($client);
        $this->smsjobs = new Smsjobs($client);
        $this->fragment = new Fragment($client);
        $this->ephemeral = new Ephemeral($client);
        $this->aicompose = new Aicompose($client);
        $this->communities = new Communities($client);
        $this->folders = new Folders($client);
        $this->stats = new Stats($client);
        $this->help = new Help($client);
        $this->upload = new Upload($client);
        $this->events = new Realtime($client);
    }

    /**
     * Factory: one line, no handler needed.
     *
     * @param string|null $database Storage dir (defaults to <cwd>/database).
     * @param string|null $proxy    SOCKS5 URL or MTProxy link (t.me/proxy, tg://proxy,
     *                              mtproxy://host:port?secret=). Falls back to BPT_PROXY.
     * @param string|null $proxyUser SOCKS5 username (or BPT_PROXY_USER).
     * @param string|null $proxyPass SOCKS5 password (or BPT_PROXY_PASS).
     */
    public static function create(
        int $apiId,
        string $apiHash,
        bool $isTest = false,
        int $dcId = 2,
        int $layer = Config::DEFAULT_LAYER,
        ?string $database = null,
        ?string $proxy = null,
        ?string $proxyUser = null,
        ?string $proxyPass = null,
    ): static {
        return new static(new Client(
            apiId: $apiId,
            apiHash: $apiHash,
            isTest: $isTest,
            dcId: $dcId,
            layer: $layer,
            storage: new Storage($database ?? (getcwd() . '/database')),
            proxy: $proxy,
            proxyUser: $proxyUser,
            proxyPass: $proxyPass,
        ));
    }

    /** Generic call for ANY TL method (complete TL body via Builder). */
    public function rpc(string $methodBody, int $retries = 3): string
    {
        return $this->client->rpc($methodBody, $retries);
    }

    /** Alias of {@see rpc()}. */
    public function raw(string $methodBody, int $retries = 3): string
    {
        return $this->client->rpc($methodBody, $retries);
    }

    /** Connected low-level API hub (advanced use). */
    public function api(): TelegramApi
    {
        return $this->client->api();
    }

    /**
     * Subscribe to a realtime event (see {@see Realtime}).
     *
     * ```php
     * $tg->on('message', fn(array $m) => print($m['text']), ['out' => false]);
     * $tg->run();
     * ```
     *
     * @param callable|array|null $filter Exact-match array or `fn(array):bool`.
     */
    public function on(string $event, callable $handler, callable|array|null $filter = null): static
    {
        $this->events->on($event, $handler, $filter);
        return $this;
    }

    /** Block and dispatch realtime events (0 = forever). */
    public function run(int $seconds = 0, int $interval = 1): void
    {
        $this->events->run($seconds, $interval);
    }

    /** Ask a running {@see run()} loop to stop. */
    public function stop(): void
    {
        $this->events->stop();
    }
}
