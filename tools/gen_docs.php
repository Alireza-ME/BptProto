<?php

declare(strict_types=1);

/**
 * Generates the "method reference" section of the docs and merges it into
 * doc/docs.json.
 *
 * For every MTProto method in tools/telegram_api.tl it emits, per namespace:
 *   h3    — method name + parameter list
 *   p     — bilingual (fa/en) description + return type
 *   table — parameters (name / type / required?optional?flag)
 *
 * Descriptions come from tools/method_descriptions.php (curated bilingual
 * wording for the common methods); the rest fall back to a derived description.
 *
 * Usage: php tools/gen_docs.php
 */

function humanize(string $name): string
{
    $s = preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $name);
    $s = str_replace(['_', '.'], ' ', $s);
    $s = strtolower(trim($s));
    return $s === '' ? $name : ucfirst($s);
}

const FA_VERBS = [
    'get' => 'دریافت', 'send' => 'ارسال', 'create' => 'ساخت', 'delete' => 'حذف',
    'edit' => 'ویرایش', 'set' => 'تنظیم', 'toggle' => 'تغییر وضعیت', 'check' => 'بررسی',
    'search' => 'جستجو', 'join' => 'عضویت', 'leave' => 'خروج', 'upload' => 'آپلود',
    'download' => 'دانلود', 'add' => 'افزودن', 'remove' => 'حذف', 'block' => 'بلاک',
    'unblock' => 'رفع بلاک', 'read' => 'خواندن', 'mark' => 'علامت‌گذاری', 'export' => 'خروجی گرفتن',
    'import' => 'وارد کردن', 'request' => 'درخواست', 'accept' => 'پذیرش', 'discard' => 'رد کردن',
    'update' => 'به‌روزرسانی', 'report' => 'گزارش', 'invite' => 'دعوت', 'hide' => 'پنهان کردن',
    'reorder' => 'مرتب‌سازی', 'install' => 'نصب', 'uninstall' => 'حذف', 'save' => 'ذخیره',
    'reset' => 'بازنشانی', 'confirm' => 'تأیید', 'change' => 'تغییر', 'register' => 'ثبت',
    'unregister' => 'لغو ثبت', 'convert' => 'تبدیل', 'start' => 'شروع', 'stop' => 'توقف',
    'rate' => 'امتیازدهی', 'view' => 'مشاهده', 'click' => 'کلیک', 'vote' => 'رأی دادن',
    'pin' => 'سنجاق', 'unpin' => 'برداشتن سنجاق', 'forward' => 'فوروارد', 'clear' => 'پاک کردن',
    'transcribe' => 'تبدیل به متن', 'translate' => 'ترجمه', 'migrate' => 'مهاجرت',
    'send' => 'ارسال', 'apply' => 'اعمال', 'prolong' => 'تمدید', 'fave' => 'افزودن به علاقه‌مندی',
];

function faFallback(string $method): string
{
    $lead = strtolower((string)preg_replace('/[A-Z].*$/', '', $method));
    $verb = FA_VERBS[$lead] ?? null;
    return $verb !== null ? $verb : 'متد `' . $method . '`';
}

function friendlyType(string $t): string
{
    $peer = [
        'InputPeer' => 'InputPeer (peer)',
        'InputChannel' => 'InputChannel (peer)',
        'InputUser' => 'InputUser (peer)',
        'InputDialogPeer' => 'InputDialogPeer (peer)',
    ];
    if (isset($peer[$t])) {
        return $peer[$t];
    }
    $map = [
        'int' => 'int', 'long' => 'long', 'double' => 'double', 'string' => 'string',
        'bytes' => 'bytes', 'int128' => 'int128', 'int256' => 'int256', 'Bool' => 'bool',
    ];
    if (isset($map[$t])) {
        return $map[$t];
    }
    if (preg_match('/^Vector<(.+)>$/', $t, $m)) {
        return friendlyType($m[1]) . '[]';
    }
    return $t;
}

function parseField(string $name, string $type): array
{
    if ($type === '#') {
        return ['kind' => 'F', 'var' => $name];
    }
    if (preg_match('/^flags\.(\d+)\?true$/', $type, $m)) {
        return ['kind' => 'B', 'name' => $name, 'bit' => (int)$m[1], 'var' => 'flags'];
    }
    if (preg_match('/^flags2\.(\d+)\?true$/', $type, $m)) {
        return ['kind' => 'B', 'name' => $name, 'bit' => (int)$m[1], 'var' => 'flags2'];
    }
    if (preg_match('/^flags\.(\d+)\?(.+)$/', $type, $m)) {
        return ['kind' => 'O', 'name' => $name, 'bit' => (int)$m[1], 'type' => $m[2], 'var' => 'flags'];
    }
    if (preg_match('/^flags2\.(\d+)\?(.+)$/', $type, $m)) {
        return ['kind' => 'O', 'name' => $name, 'bit' => (int)$m[1], 'type' => $m[2], 'var' => 'flags2'];
    }
    return ['kind' => 'R', 'name' => $name, 'type' => $type];
}

function fieldNote(array $f): array
{
    switch ($f['kind']) {
        case 'F':
            return ['fa' => 'پرچم (خودکار محاسبه می‌شود)', 'en' => 'flags (auto-computed)'];
        case 'B':
            return ['fa' => 'پرچم، بیت ' . $f['bit'], 'en' => 'flag, bit ' . $f['bit']];
        case 'O':
            return ['fa' => 'اختیاری (بیت ' . $f['bit'] . ')', 'en' => 'optional (bit ' . $f['bit'] . ')'];
        default:
            return ['fa' => 'اجباری', 'en' => 'required'];
    }
}

function buildMethodBlocks(string $ns, string $method, array $def, array $desc): array
{
    $fields = $def['fields'];
    $result = $def['result'];

    $paramNames = [];
    $rows = [];
    foreach ($fields as $f) {
        if ($f['kind'] === 'F') {
            continue;
        }
        $name = $f['name'];
        $paramNames[] = $name;
        $type = $f['kind'] === 'B' ? 'bool' : friendlyType($f['type'] ?? 'true');
        $rows[] = [$name, $type, fieldNote($f)];
    }

    $sig = $method . '(' . implode(', ', $paramNames) . ')';
    $key = $ns . '.' . $method;

    if (isset($desc[$key])) {
        $text = [
            'fa' => $desc[$key]['fa'] . ' خروجی: `' . $result . '`.',
            'en' => $desc[$key]['en'] . ' Returns `' . $result . '`.',
        ];
    } else {
        $text = [
            'fa' => faFallback($method) . '. خروجی: `' . $result . '`.',
            'en' => humanize($method) . '. Returns `' . $result . '`.',
        ];
    }

    return [
        ['t' => 'h3', 'text' => $ns . '.' . $sig],
        ['t' => 'p', 'text' => $text],
        ['t' => 'table', 'head' => [
            ['fa' => 'نام', 'en' => 'Name'],
            ['fa' => 'نوع', 'en' => 'Type'],
            ['fa' => 'وضعیت', 'en' => 'Note'],
        ], 'rows' => $rows],
    ];
}

function main(): void
{
    $root = dirname(__DIR__);
    $schemaFile = $root . '/tools/telegram_api.tl';
    $docsFile = $root . '/doc/docs.json';
    $descFile = $root . '/tools/method_descriptions.php';

    $desc = require $descFile;

    $raw = file_get_contents($schemaFile);
    if ($raw === false) {
        fwrite(STDERR, "cannot read schema\n");
        exit(1);
    }

    $byNs = [];
    $section = 'preamble';
    foreach (explode("\n", $raw) as $line) {
        $line = trim($line);
        if ($line === '---functions---') {
            $section = 'functions';
            continue;
        }
        if ($line === '---types---') {
            $section = 'types';
            continue;
        }
        if ($section !== 'functions' || $line === '' || str_contains($line, '{')) {
            continue;
        }
        if (!preg_match('/^([a-z][a-z0-9]*)\.([A-Za-z0-9]+)#([0-9a-f]+)\s+(.*?)\s*=\s*(.*);$/', $line, $m)) {
            continue;
        }
        $ns = $m[1];
        $method = $m[2];
        $argsStr = $m[4];
        $result = $m[5];
        $fields = [];
        foreach (preg_split('/\s+/', $argsStr) as $token) {
            if ($token === '') {
                continue;
            }
            $pos = strpos($token, ':');
            if ($pos === false) {
                continue;
            }
            $fields[] = parseField(substr($token, 0, $pos), substr($token, $pos + 1));
        }
        $byNs[$ns][$method] = ['fields' => $fields, 'result' => $result];
    }

    ksort($byNs);

    $pages = [];
    foreach ($byNs as $ns => $methods) {
        ksort($methods);
        $blocks = [];
        foreach ($methods as $method => $def) {
            foreach (buildMethodBlocks($ns, $method, $def, $desc) as $b) {
                $blocks[] = $b;
            }
        }
        $pages[] = [
            'id' => 'ref-' . $ns,
            'title' => $ns . '.*',
            'blocks' => $blocks,
        ];
    }

    $genSection = [
        'title' => ['fa' => 'مرجع متدها', 'en' => 'Method reference'],
        'gen' => true,
        'pages' => $pages,
    ];

    $docs = json_decode(file_get_contents($docsFile), true);
    if (!is_array($docs)) {
        fwrite(STDERR, "cannot parse docs.json\n");
        exit(1);
    }
    $docs['sections'] = array_values(array_filter(
        $docs['sections'],
        static fn(array $s) => empty($s['gen'])
    ));
    $docs['sections'][] = $genSection;

    $json = json_encode($docs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    if ($json === false) {
        fwrite(STDERR, "json encode failed: " . json_last_error_msg() . "\n");
        exit(1);
    }
    file_put_contents($docsFile, $json . "\n");

    $count = 0;
    $described = 0;
    foreach ($byNs as $ns => $methods) {
        foreach ($methods as $method => $_) {
            $count++;
            if (isset($desc[$ns . '.' . $method])) {
                $described++;
            }
        }
    }
    fwrite(STDOUT, "Generated method reference: {$count} methods (" . $described . " curated, " . ($count - $described) . " fallback) in " . count($pages) . " pages → {$docsFile}\n");
}

main();
