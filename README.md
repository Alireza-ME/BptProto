# MTProto Pure PHP — auth_key + RPC + Login + Messaging

کتابخانه‌ی **Pure PHP** (بدون وابستگی runtime) برای MTProto: handshake،
لاگین با شماره، و یک **facade ساده‌ی گروه‌بندی‌شده** برای پیام‌رسانی، مخاطبین،
کانال‌ها و رویدادهای realtime.

📖 **داک آنلاین:** https://alireza-me.github.io/BptProto/

اجرای داک به‌صورت لوکال: `php -S localhost:8000 -t docs` و سپس http://localhost:8000

## نصب

```bash
composer install          # فقط vendor/autoload.php را می‌سازد
php -m | grep -E 'gmp|openssl'   # ext-gmp و ext-openssl لازم است
```

## شروع سریع

```php
require __DIR__ . '/vendor/autoload.php';

use Bpt\BptProto;

$tg = BptProto::create(apiId: 123456, apiHash: 'your_api_hash');

// اگر شبکه‌ات تلگرام را فیلتر می‌کند، پروکسی را همین‌جا بده (پایین‌تر ببین):
// $tg = BptProto::create(apiId: 123456, apiHash: 'your_api_hash', proxy: 'socks5://127.0.0.1:1080');

// لاگین یک‌بار (اگر 2FA داری، پسورد را بده). سشن ذخیره می‌شود.
$tg->client->login('+989120000000', '12345', '2fa_password');

$me = $tg->users->getUsers(['me'])[0];                       // ['_'=>'user', 'id'=>..., 'username'=>...]
$tg->messages->sendMessage('me', 'سلام', random_int(1, PHP_INT_MAX));      // Saved Messages
$tg->messages->sendMessage('@username', 'hi', random_int(1, PHP_INT_MAX)); // resolve خودکار
$history = $tg->messages->getHistory('me', 0, 0, 0, 20, 0, 0); // ۲۰ پیام آخر
$contacts = $tg->contacts->getContacts(0);
$tg->session->close();
```

## پروکسی (فیلترینگ)

شبکه‌هایی که مستقیم به IPهای تلگرام دسترسی ندارند می‌توانند از **SOCKS5** یا
**MTProxy** استفاده کنند. پروکسی هم از کد و هم از env قابل تنظیم است
(env وقتی استفاده می‌شود که پارامتر `null` باشد).

```php
// از کد:
$tg = BptProto::create(
    apiId: 123456,
    apiHash: 'your_api_hash',
    proxy: 'https://t.me/proxy?server=HOST&port=8443&secret=SECRET', // یا 'socks5://user:pass@127.0.0.1:1080'
    // proxyUser: 'user', // فقط SOCKS5 با احراز هویت
    // proxyPass: 'pass',
);
```

```bash
# از env:
BPT_PROXY='tg://proxy?server=HOST&port=8443&secret=SECRET' php bot.php
# BPT_PROXY_USER=user  BPT_PROXY_PASS=pass  BPT_PROXY_SECRET=... (اگر در لینک نبود)
```

فرم‌های پشتیبانی‌شده:

| `proxy` | نوع |
| --- | --- |
| `socks5://[user:pass@]host:port` / `socks5h://…` | SOCKS5 (RFC 1928 + 1929) |
| `host:port` | SOCKS5 بدون اسکیما |
| `https://t.me/proxy?server=…&port=…&secret=…` / `tg://proxy?…` | MTProxy |
| `mtproxy://host:port?secret=…` | MTProxy |

نکته‌ها:

- در حالت MTProxy، تگ معمول `0xEF` فرستاده نمی‌شود؛ به‌جایش هدر ۶۴بایتی
  obfuscated2 (مطابق Telethon) ساخته می‌شود و کل استریم abridged با
  **AES-256-CTR** رمز می‌شود (`Bpt\Crypto\AesCtr`، شمارنده big-endian).
- `secret` می‌تواند hex (۳۲ کاراکتر) یا base64 (۲۲ کاراکتر) باشد.
- secretهای `dd…` (randomized-intermediate) فعلاً پشتیبانی نمی‌شوند.

## گروه‌ها

`BptProto` یک facade گروه‌بندی‌شده است: `$tg-><group>-><method>()`. تقریباً
همه‌ی گروه‌ها auto-generated از روی `telegram_api.tl` هستند؛ چند گروه برای
منطقِ خاصِ کتابخانه دست‌نویس‌اند:

| گروه | نمونه متدها |
| --- | --- |
| `client` | `login` `sendCode` `signIn` `checkPassword` (فلوی لاگین) |
| `session` | `dc` `setDc` `key` `keyId` `nearestDc` `purgeAuth` `dropSession` `close` |
| `peers` | `resolvePeer` `resolveChannel` `resolveUser` `resolveDialogPeer` `resolveUsername` |
| `updates` | `pollUpdates` `pollUpdatesRaw` `setOnline` `getDifference` `getState` `listen` |
| `files` | `uploadFile` `downloadFile` `downloadDocument` |
| `users` | `getUsers` `getFullUser` `setSecureValueErrors` |
| `messages` | `sendMessage` `sendMedia` `getHistory` `getDialogs` `getMessages` `editMessage` `deleteMessages` |
| `contacts` | `getContacts` `importContacts` `search` `resolveUsername` `block` `unblock` |
| `channels` | `joinChannel` `leaveChannel` `createChannel` `editAdmin` `getFullChannel` `getParticipants` |
| `auth` | `logOut` `signUp` `resetAuthorizations` `exportAuthorization` `importAuthorization` |
| `account` | `updateProfile` `updateStatus` `registerDevice` `setPrivacy` `getWallPapers` |
| `help` | `getConfig` `getNearestDc` `getAppConfig` `getSupport` |
| `upload` | `saveFilePart` `saveBigFilePart` `getFile` `getWebFile` |
| `bots` `payments` `phone` `photos` `stickers` `stories` `premium` `chatlists` `langpack` `smsjobs` `fragment` `ephemeral` `aicompose` `communities` `folders` `stats` | همه‌ی متدهای آن namespace |

هر متد دیگری از ۷۰۰+ متد تلگرام با TL خام:

```php
use Bpt\TL\Builder;

$raw = $tg->rpc(Builder::ctor(0xC4A353FF)->build());   // contacts.getStatuses
```

## همه‌ی متدها — facade کامل

لایه‌ی facade به‌صورت **کامل** auto-generated از روی `telegram_api.tl` است و
**مستقیم روی گروه‌های `$tg->…`** در دسترس است (بدون `gen()`): هر namespace
یک گروه دارد و **استفاده خیلی راحت است** — ورودی‌های peer خودکار resolve
می‌شوند و خروجی به‌صورت آرایه/اسکالر برمی‌گردد (نه باینری خام).

```php
// ورودی friendly + خروجی parsed
$res = $tg->messages->sendMessage('me', 'سلام', random_int(1, PHP_INT_MAX));
$ch  = $tg->channels->createChannel('عنوان', 'درباره', broadcast: true);
$full = $tg->users->getFullUser('me');
$hist = $tg->messages->getHistory('@username', 50);
$web  = $tg->messages->getWebPage('https://example.com', 0);
$me   = $tg->bots->getBotInfo('@somebot', 'en');
```

نکته‌ها:

- **یک کلاس یکدست برای هر namespace** در `src/Facade/` (auto-generated)؛ دیگر
  کلاسِ هم‌نامِ دست‌نویس وجود ندارد.
- **ورودی‌های peer خودکار resolve می‌شوند**: هر فیلد `InputPeer` / `InputChannel`
  / `InputUser` / `InputDialogPeer` (و `Vector<...>` آن‌ها) همان فرم‌های friendly
  را می‌پذیرد (`'me'`، `'@username'`، id، `['user'=>…]`، `['channel'=>…]`).
- **خروجی parsed است**: هر شیء به `['_' => نام_سازنده, …فیلدها]` تبدیل می‌شود،
  وکتورها لیست، `Bool` هم bool. مفسر: `Bpt\TL\Deserializer` + نقشه‌ی `Bpt\TL\Types`.
- امضای هر متد دقیقاً مطابق schema است: فیلدهای اجباری اول، سپس `flags.N?` به‌صورت
  `bool $x = false` / `mixed $x = null`. flags خودکار محاسبه می‌شود.
- اشیای complexِ غیر‌peer (`InputMedia`, `InputStickerSet`, …) را با
  `Bpt\TL\Peer` یا `Bpt\TL\Builder` به‌صورت blob پاس می‌دهید.
- فراخوانی‌ها از `Client::rpc()` می‌گذرند، پس retry/error/مهاجرت DC خودکار است.
- بازتولید بعد از آپدیت schema: `php tools/gen_methods.php tools/telegram_api.tl`
  (خروجی: `src/Facade/` + `src/TL/Types.php`).

## peerها

`'me'`، `'@username'`/`'username'` (یک‌بار resolve + کش در `peers.json`)،
`['user'=>id,'access_hash'=>h]`، `['channel'=>id,'access_hash'=>h]`،
`['chat'=>id]`، `'user:ID'`/`'channel:ID'`/`'chat:ID'`، id خام (کش)،
یا باینری InputPeer آماده.

فرم `type:ID` برای جواب‌دادن به آپدیت‌های ورودی راحت است (همان
`peer_type . ':' . peer_id`). اگر `access_hash` کش نباشد، برای کاربر
خودکار با `users.getUsers` گرفته و ذخیره می‌شود؛ برای کانال ابتدا از کش
و سپس `channels.getChannels` تلاش می‌شود:

```php
$tg->messages->sendMessage('user:431927914', 'سلام از BptProto', random_int(1, PHP_INT_MAX));
$tg->messages->sendMessage('channel:1234567890', 'hi', random_int(1, PHP_INT_MAX));
```

## realtime (بدون کلاس هندلر)

```php
use Bpt\BptProto;

$tg = BptProto::create(apiId: 123456, apiHash: 'your_api_hash');

$tg->on('message', function (array $m) use ($tg): void {
    if (!$m['out'] && trim($m['text']) === 'سلام') {
        $tg->messages->sendMessage($m['peer_type'] . ':' . $m['peer_id'], 'سلام از BptProto', random_int(1, PHP_INT_MAX));
    }
}, ['out' => false]);

$tg->run();   // بلاک؛ auto-reconnect — Ctrl+C برای توقف
```

نسخه‌ی کامل این نمونه در [`bpt.php`](bpt.php) است:

```bash
php bpt.php
```

### همه‌ی آپدیت‌ها (هندلر پیشرفته)

علاوه بر رویدادهای friendly، **هر ۱۶۹ سازنده‌ی `Update`** به‌صورت
**schema-driven** (با نقشه‌ی `Bpt\TL\Types`) پارس می‌شود؛ پس هیچ آپدیتی
از دست نمی‌رود و می‌توانی با **نام سازنده** هم listen کنی:

```php
$tg->on('message', fn (array $m) => /* friendly */, ['out' => false]);

$tg->on('updateUserStatus', function (array $u): void {
    // $u['user_id'], $u['status']['_'] = 'userStatusOnline' | 'userStatusOffline' | …
});

$tg->on('updateReadHistoryInbox', fn (array $u) => /* ... */);
$tg->on('update', fn (array $u) => /* همه */);   // یا '*'

$tg->events->onError(fn (\Throwable $e) => fwrite(STDERR, $e->getMessage() . "\n"));

$tg->run();
```

هر آپدیت (وقتی سرور فرستاده باشد) جدول‌های کنارِ `users` و `chats` را
همراه دارد تا فرستنده/چت را resolve کنی؛ و برای آپدیت‌های پیام، فیلدهای
friendly مثل `text`، `peer_type`، `peer_id`، `out`، `msg_id` هم پر است.

رویدادها: `message`، `edited`، `deleted`، `sent`، `too_long`،
`update`/`*` (همه)، **نام سازنده‌ی آپدیت** (مثل `updateUserStatus`)، و
`start`/`stop` (چرخه‌ی loop). فیلتر می‌تواند آرایه‌ی تطبیق دقیق باشد
(`['out' => false]`) یا `fn(array): bool`.

> `run()` یک **حلقه‌ی polling** است (نه listener رویدادمحور): هر `interval`
> (پیش‌فرض ۱ ثانیه) یک `updates.getState` + drain می‌زند. با
> `run(seconds: 60)` موقت، و با `$tg->stop()` (مثلاً داخل هندلر) تمام می‌شود.

## خطاها

- `FloodWaitException` (زیرکلاس `RpcErrorException`) با `$seconds`
- `AuthException` برای مشکلات سشن/لاگین
- مهاجرت DC (`PHONE_MIGRATE_X`) و `gzip_packed` خودکار هندل می‌شوند
- timeout حمل‌ونقل: یک‌بار reconnect با همان کلید و retry، بعد خطا —
  کلید خوب هیچ‌وقت روی timeout حدسی purge نمی‌شود
- کلید مرده فقط با مدرک صریح (`AUTH_KEY_UNREGISTERED` و...) purge +
  handshake تازه می‌شود
- `sendMessage` بعد از timeout ممکن است رسیده باشد (at-least-once) —
  قبل از ارسال مجدد history را چک کن
- اگر دو پروسه با یک `database/` اجرا شوند، دومی روی قفل
  (`another process is using this session (database/.lock)`) می‌ماند تا ۳۰
  ثانیه و بعد خطا می‌دهد — هم‌زمان فقط یک نمونه اجرا کن

## ماندگاری سشن (مثل Madeline)

یک‌بار لاگین کافی است، سشن برای همیشه می‌ماند:

- **کلید ماندگار:** `authkey_dcN.bin` هیچ‌وقت خودکار عوض نمی‌شود (handshake فقط وقتی کلیدی نیست). حتی اگر `salt`/سشن گم یا قدیمی شوند، همان کلید دوباره استفاده می‌شود تا لاگین از دست نرود.
- **ادامه سشن:** `session_dcN.bin` شامل `session_id` و شمارنده‌هاست؛ اجرای بعدی همان سشن را ادامه می‌دهد
- **چرخش salt (مثل Madeline):** تلگرام salt را (~هر ۳۰ دقیقه) عوض می‌کند. `new_session_created#9ec20908` و `bad_server_salt#edab447b` خودکار پردازش می‌شوند؛ salt و session_id تازه بلافاصله روی دیسک ذخیره و درخواست با salt جدید دوباره ارسال می‌شود — بدون لاگین مجدد.
- **قفل تک‌فرایندی:** `database/.lock` با `flock` جلوی دو فرایند هم‌زمان را می‌گیرد
- شمارنده `msg_id` با offset تصادفی شروع و `last` ذخیره می‌شود

## ساختار

```
composer.json            # PSR-4: "Bpt\\" => "src/"
src/
  BptProto.php           # facade گروه‌بندی‌شده (auth/users/messages/…)
  Client.php             # موتور اتصال + auth_key + login + migration
  Realtime.php           # event loop (on/run/stop)
  Config.php             # DC map، API layer، کلیدهای RSA عمومی
  Storage.php            # storage فایلی برای auth key/salt/hash/result
  Codec/                 # int/long/bytes (LE + padding)
  Crypto/                # AES-IGE، AES-CTR (MTProxy)، RSA، PQ، SRP (2FA)
  Transport/             # AbridgedTransport (direct/SOCKS5/MTProxy) + MsgIdGenerator
  Auth/                  # handshake کامل DH + PasswordInfo
  Session/               # EncryptedSession، TlResponse، RpcErrorException
  Api/                   # TelegramApi + wrapperهای خام هر namespace
  Entity/                # parserهای User/Message/Dialog/Updates (schema-driven)
  Facade/                # یک کلاس per namespace: auto-generated + چند دست‌نویس
  TL/                    # Builder، Reader، Ctors، Peer، Deserializer، Types
  Exception/             # AuthException، FloodWaitException
tools/                   # gen_methods.php + gen_docs.php + telegram_api.tl (schema)
docs/                    # داک آنلاین (index.html + docs.json) → GitHub Pages
  index.html             # سایت داک (fetch docs.json)
  docs.json              # محتوای داک (دوزبانه fa/en)
.github/workflows/       # pages.yml → deploy خودکار داک روی GitHub Pages
database/                # همه read/write اجرایی (git-ignored، chmod 700)
vendor/                  # خروجی Composer (git-ignored)
bpt.php                  # مثال ربات realtime پاسخ‌گو (سلام → سلام از BptProto)
```

## لایه

`Config::DEFAULT_LAYER = 229` — باید با اسکیمای بسته‌شده (`tools/telegram_api.tl`
از tdlib master، `MTPROTO_LAYER = 229`) یکی باشد. اگر لایه را پایین‌تر بدهی،
سرور سازنده‌های قدیمی برمی‌گرداند که در نقشه‌ی `Types` نیستند (مثلاً لایه‌ی
۲۲۵ `user#31774388` می‌دهد و پارس می‌شکند). Parserها شناسه‌های legacy لایه
۲۰۴ (`user#020b1422`، `message#eabcdd4d`، `dialog#d58a08c6`) را هم می‌فهمند.

## پروتکل (مطابق samples-auth_key)

1. `req_pq_multi#be7e8ef1 nonce:int128`
2. `resPQ#05162463 nonce server_nonce pq:string fingerprints:Vector<long>`
3. فاکتور `pq = p*q` , `p<q` (Pollard-Rho + `gmp_prob_prime`)
4. `p_q_inner_data_dc#a9f55f95` → `RSA_PAD` (§4.1: pad تا 192، reverse،
   `SHA256`، `AES-IGE` با IV صفر، `powm`) → 256 بایت
5. `req_DH_params#d712e4be`
6. `server_DH_params_ok#d0e8075c` → `tmp_key/iv` از `SHA1(new_nonce/server_nonce)` →
   `IGE-decrypt` → `server_DH_inner_data#b5890dba` (چک `1<g_a<p-1`، `server_time`)
7. `b` تصادفی 2048 بیتی، `g_b=pow(g,b)`، `auth_key=pow(g_a,b)` →
   `client_DH_inner_data#6643b654` → `IGE` → `set_client_DH_params#f5045f1f`
8. `auth_key` نهایی 256 بایت؛ `salt = new_nonce[0:8] XOR server_nonce[0:8]`
9. `dh_gen_ok#3bcbf734` با `new_nonce_hash1 = SHA1(new_nonce+0x01+aux)[-16:]`
10. پیام رمزنگاری MTProto2:
    `auth_key_id=SHA1(auth)[12:20]` + `msg_key=SHA256(auth[88:120]+plain)[8:24]` +
    `AES-IGE(KDF)`؛ جواب داخل `msg_container#73f1f8dc` تا `rpc_result#f35c6d01`

نکته‌های تجربی:
- `initConnection` جدید `#c1cd5ea9` است (نه `51c6a8c5`)، وگرنه `INPUT_METHOD_INVALID`.
- `msg_id` رمزنگاری باید با `server_time` باشد (`offset = server_time - time()`) وگرنه `bad_msg_notification#a7eff811` با کد 16/17.
- `PHONE_MIGRATE_4` یعنی شماره روی DC4 است و هر DC `auth_key` جدا می‌خواهد؛ `Client` خودش این را دنبال می‌کند.
- `sentCode#5e002502`: تایپ `sentCodeTypeApp#3dbb5986 length:int` بعد `phone_code_hash:string`.

## امنیت

- `database/` سکرت است (دسترسی کامل به اکانت) — `.gitignore` دارد، کامیت نکن، `chmod 700`.
- `API_HASH` سکرت است؛ در ریپو عمومی نگذار.
- `sendCode` را پشت سر هم نزن (فیلد `FLOOD_WAIT`).
- این کد آموزشی است؛ برای محصول از MadelineProto/gotd استفاده کن.
