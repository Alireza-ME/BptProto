<?php

declare(strict_types=1);

/**
 * bpt.php — a tiny realtime bot: if a user sends «سلام», reply «سلام از BptProto».
 *
 *   BPT_API_ID=123456 BPT_API_HASH=your_hash BPT_PHONE=+98912... php bpt.php
 *
 * Optional (behind censorship):
 *   BPT_PROXY='socks5://127.0.0.1:1080'  or a t.me/proxy / tg://proxy link.
 *
 * First run asks for the login code (and 2FA if enabled). Afterwards the
 * session lives in database/, so later runs go straight to listening.
 * Stop with Ctrl+C. Only ONE instance may run per database/ (flock).
 */

require __DIR__ . '/vendor/autoload.php';

use Bpt\BptProto;

$apiId = (int)(getenv('BPT_API_ID') ?: 0);
$apiHash = (string)(getenv('BPT_API_HASH') ?: '');
$phone = (string)(getenv('BPT_PHONE') ?: '');
$proxy = ($p = getenv('BPT_PROXY')) !== false && $p !== '' ? $p : null;

if ($apiId === 0 || $apiHash === '' || $phone === '') {
    fwrite(STDERR, "Set BPT_API_ID, BPT_API_HASH and BPT_PHONE (https://my.telegram.org/apps)\n");
    exit(1);
}

$tg = BptProto::create(apiId: $apiId, apiHash: $apiHash, proxy: $proxy);

try {
    $me = $tg->users->getFullUser('me');
    echo '---------- hi ----------' . "\n";
    print_r($me);
} catch (\Bpt\Exception\AuthException $e) {
    // First run / dead session → interactive login (network/lock errors are NOT swallowed).
    $tg->client->sendCode($phone);
    echo 'Enter code: ';
    $code = trim(fgets(STDIN));
    $sign = $tg->auth->signIn($phone, $code);
    echo "\n" . '---------- SignIn----------' . "\n";
    print_r($sign);
}

// Any failure inside the loop (network, flood-wait, handler) is surfaced here.
$tg->events->onError(function (\Throwable $e): void {
    fwrite(STDERR, '[realtime error] ' . get_class($e) . ': ' . $e->getMessage() . "\n");
});

$tg->on('message', function (array $m) use ($tg): void {
    fwrite(STDERR, sprintf("[message] %s:%d » %s\n", $m['peer_type'], $m['peer_id'], $m['text']));

    if (trim((string)($m['text'] ?? '')) === 'سلام') {
        $tg->messages->sendMessage(
            $m['peer_type'] . ':' . $m['peer_id'],
            'سلام از BptProto',
            random_int(1, PHP_INT_MAX)
        );
        fwrite(STDERR, sprintf("[reply] sent to %s:%d\n", $m['peer_type'], $m['peer_id']));
    }
}, ['out' => false]);

$tg->run();
