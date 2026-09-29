<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every account.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Account extends Group
{

    /**
     * account.acceptAuthorization#f3ed4c73 = Bool.
     */
    public function acceptAuthorization(int $bot_id, string $scope, string $public_key, array $value_hashes, string $credentials): mixed
    {
        $b = Builder::ctor(0xF3ED4C73);
        $b->long((int)$bot_id);
        $b->string((string)$scope);
        $b->string((string)$public_key);
        $b->vector($value_hashes);
        $b->rawBlob($credentials);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.cancelPasswordEmail#c1cbd5b6 = Bool.
     */
    public function cancelPasswordEmail(): mixed
    {
        $b = Builder::ctor(0xC1CBD5B6);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.changeAuthorizationSettings#40f48462 = Bool.
     */
    public function changeAuthorizationSettings(int $hash, bool $confirmed = false, ?bool $encrypted_requests_disabled = null, ?bool $call_requests_disabled = null): mixed
    {
        $flags = 0;
        if ($confirmed) { $flags |= (1 << 3); }
        if ($encrypted_requests_disabled !== null) { $flags |= (1 << 0); }
        if ($call_requests_disabled !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x40F48462);
        $b->int($flags);
        $b->long((int)$hash);
        if ($encrypted_requests_disabled !== null) { $b->bool((bool)$encrypted_requests_disabled); }
        if ($call_requests_disabled !== null) { $b->bool((bool)$call_requests_disabled); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.changePhone#70c32edb = User.
     */
    public function changePhone(string $phone_number, string $phone_code_hash, string $phone_code): mixed
    {
        $b = Builder::ctor(0x70C32EDB);
        $b->string((string)$phone_number);
        $b->string((string)$phone_code_hash);
        $b->string((string)$phone_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'User');
    }

    /**
     * account.checkUsername#2714d86c = Bool.
     */
    public function checkUsername(string $username): mixed
    {
        $b = Builder::ctor(0x2714D86C);
        $b->string((string)$username);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.clearRecentEmojiStatuses#18201aae = Bool.
     */
    public function clearRecentEmojiStatuses(): mixed
    {
        $b = Builder::ctor(0x18201AAE);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.confirmBotConnection#67ed1f68 = Bool.
     */
    public function confirmBotConnection(mixed $bot_id): mixed
    {
        $b = Builder::ctor(0x67ED1F68);
        $b->rawBlob($this->peers()->resolveUser($bot_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.confirmPasswordEmail#8fdf1920 = Bool.
     */
    public function confirmPasswordEmail(string $code): mixed
    {
        $b = Builder::ctor(0x8FDF1920);
        $b->string((string)$code);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.confirmPhone#5f2178c3 = Bool.
     */
    public function confirmPhone(string $phone_code_hash, string $phone_code): mixed
    {
        $b = Builder::ctor(0x5F2178C3);
        $b->string((string)$phone_code_hash);
        $b->string((string)$phone_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.createBusinessChatLink#8851e68e = BusinessChatLink.
     */
    public function createBusinessChatLink(string $link): mixed
    {
        $b = Builder::ctor(0x8851E68E);
        $b->rawBlob($link);
        return Deserializer::parse($this->client->rpc($b->build()), 'BusinessChatLink');
    }

    /**
     * account.createTheme#652e4400 = Theme.
     */
    public function createTheme(string $slug, string $title, ?string $document = null, ?array $settings = null): mixed
    {
        $flags = 0;
        if ($document !== null) { $flags |= (1 << 2); }
        if ($settings !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x652E4400);
        $b->int($flags);
        $b->string((string)$slug);
        $b->string((string)$title);
        if ($document !== null) { $b->rawBlob($document); }
        if ($settings !== null) { $b->vector($settings); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Theme');
    }

    /**
     * account.declinePasswordReset#4c9409f6 = Bool.
     */
    public function declinePasswordReset(): mixed
    {
        $b = Builder::ctor(0x4C9409F6);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.deleteAccount#a2c0cf74 = Bool.
     */
    public function deleteAccount(string $reason, ?string $password = null): mixed
    {
        $flags = 0;
        if ($password !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xA2C0CF74);
        $b->int($flags);
        $b->string((string)$reason);
        if ($password !== null) { $b->rawBlob($password); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.deleteAutoSaveExceptions#53bc0020 = Bool.
     */
    public function deleteAutoSaveExceptions(): mixed
    {
        $b = Builder::ctor(0x53BC0020);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.deleteBusinessChatLink#60073674 = Bool.
     */
    public function deleteBusinessChatLink(string $slug): mixed
    {
        $b = Builder::ctor(0x60073674);
        $b->string((string)$slug);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.deletePasskey#f5b5563f = Bool.
     */
    public function deletePasskey(string $id): mixed
    {
        $b = Builder::ctor(0xF5B5563F);
        $b->string((string)$id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.deleteSecureValue#b880bc4b = Bool.
     */
    public function deleteSecureValue(array $types): mixed
    {
        $b = Builder::ctor(0xB880BC4B);
        $b->vector($types);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.deleteWebBrowserSettingsExceptions#86a0765d = account.WebBrowserSettings.
     */
    public function deleteWebBrowserSettingsExceptions(): mixed
    {
        $b = Builder::ctor(0x86A0765D);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.WebBrowserSettings');
    }

    /**
     * account.disablePeerConnectedBot#5e437ed9 = Bool.
     */
    public function disablePeerConnectedBot(mixed $peer): mixed
    {
        $b = Builder::ctor(0x5E437ED9);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.editBusinessChatLink#8c3410af = BusinessChatLink.
     */
    public function editBusinessChatLink(string $slug, string $link): mixed
    {
        $b = Builder::ctor(0x8C3410AF);
        $b->string((string)$slug);
        $b->rawBlob($link);
        return Deserializer::parse($this->client->rpc($b->build()), 'BusinessChatLink');
    }

    /**
     * account.finishTakeoutSession#1d2652ee = Bool.
     */
    public function finishTakeoutSession(bool $success = false): mixed
    {
        $flags = 0;
        if ($success) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x1D2652EE);
        $b->int($flags);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.getAccountTTL#8fc711d = AccountDaysTTL.
     */
    public function getAccountTTL(): mixed
    {
        $b = Builder::ctor(0x8FC711D);
        return Deserializer::parse($this->client->rpc($b->build()), 'AccountDaysTTL');
    }

    /**
     * account.getAllSecureValues#b288bc7d = Vector<SecureValue>.
     */
    public function getAllSecureValues(): mixed
    {
        $b = Builder::ctor(0xB288BC7D);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<SecureValue>');
    }

    /**
     * account.getAuthorizationForm#a929597a = account.AuthorizationForm.
     */
    public function getAuthorizationForm(int $bot_id, string $scope, string $public_key): mixed
    {
        $b = Builder::ctor(0xA929597A);
        $b->long((int)$bot_id);
        $b->string((string)$scope);
        $b->string((string)$public_key);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.AuthorizationForm');
    }

    /**
     * account.getAuthorizations#e320c158 = account.Authorizations.
     */
    public function getAuthorizations(): mixed
    {
        $b = Builder::ctor(0xE320C158);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.Authorizations');
    }

    /**
     * account.getAutoDownloadSettings#56da0b3f = account.AutoDownloadSettings.
     */
    public function getAutoDownloadSettings(): mixed
    {
        $b = Builder::ctor(0x56DA0B3F);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.AutoDownloadSettings');
    }

    /**
     * account.getAutoSaveSettings#adcbbcda = account.AutoSaveSettings.
     */
    public function getAutoSaveSettings(): mixed
    {
        $b = Builder::ctor(0xADCBBCDA);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.AutoSaveSettings');
    }

    /**
     * account.getBotBusinessConnection#76a86270 = Updates.
     */
    public function getBotBusinessConnection(string $connection_id): mixed
    {
        $b = Builder::ctor(0x76A86270);
        $b->string((string)$connection_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * account.getBusinessChatLinks#6f70dde1 = account.BusinessChatLinks.
     */
    public function getBusinessChatLinks(): mixed
    {
        $b = Builder::ctor(0x6F70DDE1);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.BusinessChatLinks');
    }

    /**
     * account.getChannelDefaultEmojiStatuses#7727a7d5 = account.EmojiStatuses.
     */
    public function getChannelDefaultEmojiStatuses(int $hash): mixed
    {
        $b = Builder::ctor(0x7727A7D5);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.EmojiStatuses');
    }

    /**
     * account.getChannelRestrictedStatusEmojis#35a9e0d5 = EmojiList.
     */
    public function getChannelRestrictedStatusEmojis(int $hash): mixed
    {
        $b = Builder::ctor(0x35A9E0D5);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'EmojiList');
    }

    /**
     * account.getChatThemes#d638de89 = account.Themes.
     */
    public function getChatThemes(int $hash): mixed
    {
        $b = Builder::ctor(0xD638DE89);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.Themes');
    }

    /**
     * account.getCollectibleEmojiStatuses#2e7b4543 = account.EmojiStatuses.
     */
    public function getCollectibleEmojiStatuses(int $hash): mixed
    {
        $b = Builder::ctor(0x2E7B4543);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.EmojiStatuses');
    }

    /**
     * account.getConnectedBots#4ea4c80f = account.ConnectedBots.
     */
    public function getConnectedBots(): mixed
    {
        $b = Builder::ctor(0x4EA4C80F);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.ConnectedBots');
    }

    /**
     * account.getContactSignUpNotification#9f07c728 = Bool.
     */
    public function getContactSignUpNotification(): mixed
    {
        $b = Builder::ctor(0x9F07C728);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.getContentSettings#8b9b4dae = account.ContentSettings.
     */
    public function getContentSettings(): mixed
    {
        $b = Builder::ctor(0x8B9B4DAE);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.ContentSettings');
    }

    /**
     * account.getDefaultBackgroundEmojis#a60ab9ce = EmojiList.
     */
    public function getDefaultBackgroundEmojis(int $hash): mixed
    {
        $b = Builder::ctor(0xA60AB9CE);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'EmojiList');
    }

    /**
     * account.getDefaultEmojiStatuses#d6753386 = account.EmojiStatuses.
     */
    public function getDefaultEmojiStatuses(int $hash): mixed
    {
        $b = Builder::ctor(0xD6753386);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.EmojiStatuses');
    }

    /**
     * account.getDefaultGroupPhotoEmojis#915860ae = EmojiList.
     */
    public function getDefaultGroupPhotoEmojis(int $hash): mixed
    {
        $b = Builder::ctor(0x915860AE);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'EmojiList');
    }

    /**
     * account.getDefaultProfilePhotoEmojis#e2750328 = EmojiList.
     */
    public function getDefaultProfilePhotoEmojis(int $hash): mixed
    {
        $b = Builder::ctor(0xE2750328);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'EmojiList');
    }

    /**
     * account.getGlobalPrivacySettings#eb2b4cf6 = GlobalPrivacySettings.
     */
    public function getGlobalPrivacySettings(): mixed
    {
        $b = Builder::ctor(0xEB2B4CF6);
        return Deserializer::parse($this->client->rpc($b->build()), 'GlobalPrivacySettings');
    }

    /**
     * account.getMultiWallPapers#65ad71dc = Vector<WallPaper>.
     */
    public function getMultiWallPapers(array $wallpapers): mixed
    {
        $b = Builder::ctor(0x65AD71DC);
        $b->vector($wallpapers);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<WallPaper>');
    }

    /**
     * account.getNotifyExceptions#53577479 = Updates.
     */
    public function getNotifyExceptions(bool $compare_sound = false, bool $compare_stories = false, ?string $peer = null): mixed
    {
        $flags = 0;
        if ($compare_sound) { $flags |= (1 << 1); }
        if ($compare_stories) { $flags |= (1 << 2); }
        if ($peer !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x53577479);
        $b->int($flags);
        if ($peer !== null) { $b->rawBlob($peer); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * account.getNotifySettings#12b3ad31 = PeerNotifySettings.
     */
    public function getNotifySettings(string $peer): mixed
    {
        $b = Builder::ctor(0x12B3AD31);
        $b->rawBlob($peer);
        return Deserializer::parse($this->client->rpc($b->build()), 'PeerNotifySettings');
    }

    /**
     * account.getPaidMessagesRevenue#19ba4a67 = account.PaidMessagesRevenue.
     */
    public function getPaidMessagesRevenue(mixed $user_id, mixed $parent_peer = null): mixed
    {
        $flags = 0;
        if ($parent_peer !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x19BA4A67);
        $b->int($flags);
        if ($parent_peer !== null) { $b->rawBlob($this->peerBlob($parent_peer)); }
        $b->rawBlob($this->peers()->resolveUser($user_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'account.PaidMessagesRevenue');
    }

    /**
     * account.getPasskeys#ea1f0c52 = account.Passkeys.
     */
    public function getPasskeys(): mixed
    {
        $b = Builder::ctor(0xEA1F0C52);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.Passkeys');
    }

    /**
     * account.getPassword#548a30f5 = account.Password.
     */
    public function getPassword(): mixed
    {
        $b = Builder::ctor(0x548A30F5);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.Password');
    }

    /**
     * account.getPasswordSettings#9cd4eaf9 = account.PasswordSettings.
     */
    public function getPasswordSettings(string $password): mixed
    {
        $b = Builder::ctor(0x9CD4EAF9);
        $b->rawBlob($password);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.PasswordSettings');
    }

    /**
     * account.getPrivacy#dadbc950 = account.PrivacyRules.
     */
    public function getPrivacy(string $key): mixed
    {
        $b = Builder::ctor(0xDADBC950);
        $b->rawBlob($key);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.PrivacyRules');
    }

    /**
     * account.getReactionsNotifySettings#6dd654c = ReactionsNotifySettings.
     */
    public function getReactionsNotifySettings(): mixed
    {
        $b = Builder::ctor(0x6DD654C);
        return Deserializer::parse($this->client->rpc($b->build()), 'ReactionsNotifySettings');
    }

    /**
     * account.getRecentEmojiStatuses#f578105 = account.EmojiStatuses.
     */
    public function getRecentEmojiStatuses(int $hash): mixed
    {
        $b = Builder::ctor(0xF578105);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.EmojiStatuses');
    }

    /**
     * account.getSavedMusicIds#e09d5faf = account.SavedMusicIds.
     */
    public function getSavedMusicIds(int $hash): mixed
    {
        $b = Builder::ctor(0xE09D5FAF);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.SavedMusicIds');
    }

    /**
     * account.getSavedRingtones#e1902288 = account.SavedRingtones.
     */
    public function getSavedRingtones(int $hash): mixed
    {
        $b = Builder::ctor(0xE1902288);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.SavedRingtones');
    }

    /**
     * account.getSecureValue#73665bc2 = Vector<SecureValue>.
     */
    public function getSecureValue(array $types): mixed
    {
        $b = Builder::ctor(0x73665BC2);
        $b->vector($types);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<SecureValue>');
    }

    /**
     * account.getTheme#3a5869ec = Theme.
     */
    public function getTheme(string $format, string $theme): mixed
    {
        $b = Builder::ctor(0x3A5869EC);
        $b->string((string)$format);
        $b->rawBlob($theme);
        return Deserializer::parse($this->client->rpc($b->build()), 'Theme');
    }

    /**
     * account.getThemes#7206e458 = account.Themes.
     */
    public function getThemes(string $format, int $hash): mixed
    {
        $b = Builder::ctor(0x7206E458);
        $b->string((string)$format);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.Themes');
    }

    /**
     * account.getTmpPassword#449e0b51 = account.TmpPassword.
     */
    public function getTmpPassword(string $password, int $period): mixed
    {
        $b = Builder::ctor(0x449E0B51);
        $b->rawBlob($password);
        $b->int((int)$period);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.TmpPassword');
    }

    /**
     * account.getUniqueGiftChatThemes#e42ce9c9 = account.ChatThemes.
     */
    public function getUniqueGiftChatThemes(string $offset, int $limit, int $hash): mixed
    {
        $b = Builder::ctor(0xE42CE9C9);
        $b->string((string)$offset);
        $b->int((int)$limit);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.ChatThemes');
    }

    /**
     * account.getWallPaper#fc8ddbea = WallPaper.
     */
    public function getWallPaper(string $wallpaper): mixed
    {
        $b = Builder::ctor(0xFC8DDBEA);
        $b->rawBlob($wallpaper);
        return Deserializer::parse($this->client->rpc($b->build()), 'WallPaper');
    }

    /**
     * account.getWallPapers#7967d36 = account.WallPapers.
     */
    public function getWallPapers(int $hash): mixed
    {
        $b = Builder::ctor(0x7967D36);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.WallPapers');
    }

    /**
     * account.getWebAuthorizations#182e6d6f = account.WebAuthorizations.
     */
    public function getWebAuthorizations(): mixed
    {
        $b = Builder::ctor(0x182E6D6F);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.WebAuthorizations');
    }

    /**
     * account.getWebBrowserSettings#56655768 = account.WebBrowserSettings.
     */
    public function getWebBrowserSettings(int $hash): mixed
    {
        $b = Builder::ctor(0x56655768);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.WebBrowserSettings');
    }

    /**
     * account.initPasskeyRegistration#429547e8 = account.PasskeyRegistrationOptions.
     */
    public function initPasskeyRegistration(): mixed
    {
        $b = Builder::ctor(0x429547E8);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.PasskeyRegistrationOptions');
    }

    /**
     * account.initTakeoutSession#8ef3eab0 = account.Takeout.
     */
    public function initTakeoutSession(bool $contacts = false, bool $message_users = false, bool $message_chats = false, bool $message_megagroups = false, bool $message_channels = false, bool $files = false, ?int $file_max_size = null): mixed
    {
        $flags = 0;
        if ($contacts) { $flags |= (1 << 0); }
        if ($message_users) { $flags |= (1 << 1); }
        if ($message_chats) { $flags |= (1 << 2); }
        if ($message_megagroups) { $flags |= (1 << 3); }
        if ($message_channels) { $flags |= (1 << 4); }
        if ($files) { $flags |= (1 << 5); }
        if ($file_max_size !== null) { $flags |= (1 << 5); }
        $b = Builder::ctor(0x8EF3EAB0);
        $b->int($flags);
        if ($file_max_size !== null) { $b->long((int)$file_max_size); }
        return Deserializer::parse($this->client->rpc($b->build()), 'account.Takeout');
    }

    /**
     * account.installTheme#c727bb3b = Bool.
     */
    public function installTheme(bool $dark = false, ?string $theme = null, ?string $format = null, ?string $base_theme = null): mixed
    {
        $flags = 0;
        if ($dark) { $flags |= (1 << 0); }
        if ($theme !== null) { $flags |= (1 << 1); }
        if ($format !== null) { $flags |= (1 << 2); }
        if ($base_theme !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0xC727BB3B);
        $b->int($flags);
        if ($theme !== null) { $b->rawBlob($theme); }
        if ($format !== null) { $b->string((string)$format); }
        if ($base_theme !== null) { $b->rawBlob($base_theme); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.installWallPaper#feed5769 = Bool.
     */
    public function installWallPaper(string $wallpaper, string $settings): mixed
    {
        $b = Builder::ctor(0xFEED5769);
        $b->rawBlob($wallpaper);
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.invalidateSignInCodes#ca8ae8ba = Bool.
     */
    public function invalidateSignInCodes(array $codes): mixed
    {
        $b = Builder::ctor(0xCA8AE8BA);
        $b->vectorString($codes);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.registerDevice#ec86017a = Bool.
     */
    public function registerDevice(int $token_type, string $token, bool $app_sandbox, string $secret, array $other_uids, bool $no_muted = false): mixed
    {
        $flags = 0;
        if ($no_muted) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xEC86017A);
        $b->int($flags);
        $b->int((int)$token_type);
        $b->string((string)$token);
        $b->bool((bool)$app_sandbox);
        $b->string((string)$secret);
        $b->vectorLong($other_uids);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.registerPasskey#55b41fd6 = Passkey.
     */
    public function registerPasskey(string $credential): mixed
    {
        $b = Builder::ctor(0x55B41FD6);
        $b->rawBlob($credential);
        return Deserializer::parse($this->client->rpc($b->build()), 'Passkey');
    }

    /**
     * account.reorderUsernames#ef500eab = Bool.
     */
    public function reorderUsernames(array $order): mixed
    {
        $b = Builder::ctor(0xEF500EAB);
        $b->vectorString($order);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.reportPeer#c5ba3d86 = Bool.
     */
    public function reportPeer(mixed $peer, string $reason, string $message): mixed
    {
        $b = Builder::ctor(0xC5BA3D86);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($reason);
        $b->string((string)$message);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.reportProfilePhoto#fa8cc6f5 = Bool.
     */
    public function reportProfilePhoto(mixed $peer, string $photo_id, string $reason, string $message): mixed
    {
        $b = Builder::ctor(0xFA8CC6F5);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($photo_id);
        $b->rawBlob($reason);
        $b->string((string)$message);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.resendPasswordEmail#7a7f2a15 = Bool.
     */
    public function resendPasswordEmail(): mixed
    {
        $b = Builder::ctor(0x7A7F2A15);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.resetAuthorization#df77f3bc = Bool.
     */
    public function resetAuthorization(int $hash): mixed
    {
        $b = Builder::ctor(0xDF77F3BC);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.resetNotifySettings#db7e1747 = Bool.
     */
    public function resetNotifySettings(): mixed
    {
        $b = Builder::ctor(0xDB7E1747);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.resetPassword#9308ce1b = account.ResetPasswordResult.
     */
    public function resetPassword(): mixed
    {
        $b = Builder::ctor(0x9308CE1B);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.ResetPasswordResult');
    }

    /**
     * account.resetWallPapers#bb3b9804 = Bool.
     */
    public function resetWallPapers(): mixed
    {
        $b = Builder::ctor(0xBB3B9804);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.resetWebAuthorization#2d01b9ef = Bool.
     */
    public function resetWebAuthorization(int $hash): mixed
    {
        $b = Builder::ctor(0x2D01B9EF);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.resetWebAuthorizations#682d2594 = Bool.
     */
    public function resetWebAuthorizations(): mixed
    {
        $b = Builder::ctor(0x682D2594);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.resolveBusinessChatLink#5492e5ee = account.ResolvedBusinessChatLinks.
     */
    public function resolveBusinessChatLink(string $slug): mixed
    {
        $b = Builder::ctor(0x5492E5EE);
        $b->string((string)$slug);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.ResolvedBusinessChatLinks');
    }

    /**
     * account.saveAutoDownloadSettings#76f36233 = Bool.
     */
    public function saveAutoDownloadSettings(string $settings, bool $low = false, bool $high = false): mixed
    {
        $flags = 0;
        if ($low) { $flags |= (1 << 0); }
        if ($high) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x76F36233);
        $b->int($flags);
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.saveAutoSaveSettings#d69b8361 = Bool.
     */
    public function saveAutoSaveSettings(string $settings, bool $users = false, bool $chats = false, bool $broadcasts = false, mixed $peer = null): mixed
    {
        $flags = 0;
        if ($users) { $flags |= (1 << 0); }
        if ($chats) { $flags |= (1 << 1); }
        if ($broadcasts) { $flags |= (1 << 2); }
        if ($peer !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0xD69B8361);
        $b->int($flags);
        if ($peer !== null) { $b->rawBlob($this->peerBlob($peer)); }
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.saveMusic#b26732a9 = Bool.
     */
    public function saveMusic(string $id, bool $unsave = false, ?string $after_id = null): mixed
    {
        $flags = 0;
        if ($unsave) { $flags |= (1 << 0); }
        if ($after_id !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xB26732A9);
        $b->int($flags);
        $b->rawBlob($id);
        if ($after_id !== null) { $b->rawBlob($after_id); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.saveRingtone#3dea5b03 = account.SavedRingtone.
     */
    public function saveRingtone(string $id, bool $unsave): mixed
    {
        $b = Builder::ctor(0x3DEA5B03);
        $b->rawBlob($id);
        $b->bool((bool)$unsave);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.SavedRingtone');
    }

    /**
     * account.saveSecureValue#899fe31d = SecureValue.
     */
    public function saveSecureValue(string $value, int $secure_secret_id): mixed
    {
        $b = Builder::ctor(0x899FE31D);
        $b->rawBlob($value);
        $b->long((int)$secure_secret_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'SecureValue');
    }

    /**
     * account.saveTheme#f257106c = Bool.
     */
    public function saveTheme(string $theme, bool $unsave): mixed
    {
        $b = Builder::ctor(0xF257106C);
        $b->rawBlob($theme);
        $b->bool((bool)$unsave);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.saveWallPaper#6c5a5b37 = Bool.
     */
    public function saveWallPaper(string $wallpaper, bool $unsave, string $settings): mixed
    {
        $b = Builder::ctor(0x6C5A5B37);
        $b->rawBlob($wallpaper);
        $b->bool((bool)$unsave);
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.sendChangePhoneCode#82574ae5 = auth.SentCode.
     */
    public function sendChangePhoneCode(string $phone_number, string $settings): mixed
    {
        $b = Builder::ctor(0x82574AE5);
        $b->string((string)$phone_number);
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.SentCode');
    }

    /**
     * account.sendConfirmPhoneCode#1b3faa88 = auth.SentCode.
     */
    public function sendConfirmPhoneCode(string $hash, string $settings): mixed
    {
        $b = Builder::ctor(0x1B3FAA88);
        $b->string((string)$hash);
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.SentCode');
    }

    /**
     * account.sendVerifyEmailCode#98e037bb = account.SentEmailCode.
     */
    public function sendVerifyEmailCode(string $purpose, string $email): mixed
    {
        $b = Builder::ctor(0x98E037BB);
        $b->rawBlob($purpose);
        $b->string((string)$email);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.SentEmailCode');
    }

    /**
     * account.sendVerifyPhoneCode#a5a356f9 = auth.SentCode.
     */
    public function sendVerifyPhoneCode(string $phone_number, string $settings): mixed
    {
        $b = Builder::ctor(0xA5A356F9);
        $b->string((string)$phone_number);
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.SentCode');
    }

    /**
     * account.setAccountTTL#2442485e = Bool.
     */
    public function setAccountTTL(string $ttl): mixed
    {
        $b = Builder::ctor(0x2442485E);
        $b->rawBlob($ttl);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.setAuthorizationTTL#bf899aa0 = Bool.
     */
    public function setAuthorizationTTL(int $authorization_ttl_days): mixed
    {
        $b = Builder::ctor(0xBF899AA0);
        $b->int((int)$authorization_ttl_days);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.setContactSignUpNotification#cff43f61 = Bool.
     */
    public function setContactSignUpNotification(bool $silent): mixed
    {
        $b = Builder::ctor(0xCFF43F61);
        $b->bool((bool)$silent);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.setContentSettings#b574b16b = Bool.
     */
    public function setContentSettings(bool $sensitive_enabled = false): mixed
    {
        $flags = 0;
        if ($sensitive_enabled) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xB574B16B);
        $b->int($flags);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.setGlobalPrivacySettings#1edaaac2 = GlobalPrivacySettings.
     */
    public function setGlobalPrivacySettings(string $settings): mixed
    {
        $b = Builder::ctor(0x1EDAAAC2);
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'GlobalPrivacySettings');
    }

    /**
     * account.setMainProfileTab#5dee78b0 = Bool.
     */
    public function setMainProfileTab(string $tab): mixed
    {
        $b = Builder::ctor(0x5DEE78B0);
        $b->rawBlob($tab);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.setPrivacy#c9f81ce8 = account.PrivacyRules.
     */
    public function setPrivacy(string $key, array $rules): mixed
    {
        $b = Builder::ctor(0xC9F81CE8);
        $b->rawBlob($key);
        $b->vector($rules);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.PrivacyRules');
    }

    /**
     * account.setReactionsNotifySettings#316ce548 = ReactionsNotifySettings.
     */
    public function setReactionsNotifySettings(string $settings): mixed
    {
        $b = Builder::ctor(0x316CE548);
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'ReactionsNotifySettings');
    }

    /**
     * account.toggleConnectedBotPaused#646e1097 = Bool.
     */
    public function toggleConnectedBotPaused(mixed $peer, bool $paused): mixed
    {
        $b = Builder::ctor(0x646E1097);
        $b->rawBlob($this->peerBlob($peer));
        $b->bool((bool)$paused);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.toggleNoPaidMessagesException#fe2eda76 = Bool.
     */
    public function toggleNoPaidMessagesException(mixed $user_id, bool $refund_charged = false, bool $require_payment = false, mixed $parent_peer = null): mixed
    {
        $flags = 0;
        if ($refund_charged) { $flags |= (1 << 0); }
        if ($require_payment) { $flags |= (1 << 2); }
        if ($parent_peer !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xFE2EDA76);
        $b->int($flags);
        if ($parent_peer !== null) { $b->rawBlob($this->peerBlob($parent_peer)); }
        $b->rawBlob($this->peers()->resolveUser($user_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.toggleSponsoredMessages#b9d9a38d = Bool.
     */
    public function toggleSponsoredMessages(bool $enabled): mixed
    {
        $b = Builder::ctor(0xB9D9A38D);
        $b->bool((bool)$enabled);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.toggleUsername#58d6b376 = Bool.
     */
    public function toggleUsername(string $username, bool $active): mixed
    {
        $b = Builder::ctor(0x58D6B376);
        $b->string((string)$username);
        $b->bool((bool)$active);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.toggleWebBrowserSettingsException#60ed4229 = Updates.
     */
    public function toggleWebBrowserSettingsException(string $url, bool $delete = false, ?bool $open_external_browser = null): mixed
    {
        $flags = 0;
        if ($delete) { $flags |= (1 << 1); }
        if ($open_external_browser !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x60ED4229);
        $b->int($flags);
        if ($open_external_browser !== null) { $b->bool((bool)$open_external_browser); }
        $b->string((string)$url);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * account.unregisterDevice#6a0d3206 = Bool.
     */
    public function unregisterDevice(int $token_type, string $token, array $other_uids): mixed
    {
        $b = Builder::ctor(0x6A0D3206);
        $b->int((int)$token_type);
        $b->string((string)$token);
        $b->vectorLong($other_uids);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateBirthday#cc6e0c11 = Bool.
     */
    public function updateBirthday(?string $birthday = null): mixed
    {
        $flags = 0;
        if ($birthday !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xCC6E0C11);
        $b->int($flags);
        if ($birthday !== null) { $b->rawBlob($birthday); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateBusinessAwayMessage#a26a7fa5 = Bool.
     */
    public function updateBusinessAwayMessage(?string $message = null): mixed
    {
        $flags = 0;
        if ($message !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xA26A7FA5);
        $b->int($flags);
        if ($message !== null) { $b->rawBlob($message); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateBusinessGreetingMessage#66cdafc4 = Bool.
     */
    public function updateBusinessGreetingMessage(?string $message = null): mixed
    {
        $flags = 0;
        if ($message !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x66CDAFC4);
        $b->int($flags);
        if ($message !== null) { $b->rawBlob($message); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateBusinessIntro#a614d034 = Bool.
     */
    public function updateBusinessIntro(?string $intro = null): mixed
    {
        $flags = 0;
        if ($intro !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xA614D034);
        $b->int($flags);
        if ($intro !== null) { $b->rawBlob($intro); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateBusinessLocation#9e6b131a = Bool.
     */
    public function updateBusinessLocation(?string $geo_point = null, ?string $address = null): mixed
    {
        $flags = 0;
        if ($geo_point !== null) { $flags |= (1 << 1); }
        if ($address !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x9E6B131A);
        $b->int($flags);
        if ($geo_point !== null) { $b->rawBlob($geo_point); }
        if ($address !== null) { $b->string((string)$address); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateBusinessWorkHours#4b00e066 = Bool.
     */
    public function updateBusinessWorkHours(?string $business_work_hours = null): mixed
    {
        $flags = 0;
        if ($business_work_hours !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x4B00E066);
        $b->int($flags);
        if ($business_work_hours !== null) { $b->rawBlob($business_work_hours); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateColor#684d214e = Bool.
     */
    public function updateColor(bool $for_profile = false, ?string $color = null): mixed
    {
        $flags = 0;
        if ($for_profile) { $flags |= (1 << 1); }
        if ($color !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x684D214E);
        $b->int($flags);
        if ($color !== null) { $b->rawBlob($color); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateConnectedBot#66a08c7e = Updates.
     */
    public function updateConnectedBot(mixed $bot, string $recipients, bool $deleted = false, ?string $rights = null): mixed
    {
        $flags = 0;
        if ($deleted) { $flags |= (1 << 1); }
        if ($rights !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x66A08C7E);
        $b->int($flags);
        if ($rights !== null) { $b->rawBlob($rights); }
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->rawBlob($recipients);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * account.updateDeviceLocked#38df3532 = Bool.
     */
    public function updateDeviceLocked(int $period): mixed
    {
        $b = Builder::ctor(0x38DF3532);
        $b->int((int)$period);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateEmojiStatus#fbd3de6b = Bool.
     */
    public function updateEmojiStatus(string $emoji_status): mixed
    {
        $b = Builder::ctor(0xFBD3DE6B);
        $b->rawBlob($emoji_status);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateNotifySettings#84be5b93 = Bool.
     */
    public function updateNotifySettings(string $peer, string $settings): mixed
    {
        $b = Builder::ctor(0x84BE5B93);
        $b->rawBlob($peer);
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updatePasswordSettings#a59b102f = Bool.
     */
    public function updatePasswordSettings(string $password, string $new_settings): mixed
    {
        $b = Builder::ctor(0xA59B102F);
        $b->rawBlob($password);
        $b->rawBlob($new_settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updatePersonalChannel#d94305e0 = Bool.
     */
    public function updatePersonalChannel(mixed $channel): mixed
    {
        $b = Builder::ctor(0xD94305E0);
        $b->rawBlob($this->channelBlob($channel));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateProfile#78515775 = User.
     */
    public function updateProfile(?string $first_name = null, ?string $last_name = null, ?string $about = null): mixed
    {
        $flags = 0;
        if ($first_name !== null) { $flags |= (1 << 0); }
        if ($last_name !== null) { $flags |= (1 << 1); }
        if ($about !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x78515775);
        $b->int($flags);
        if ($first_name !== null) { $b->string((string)$first_name); }
        if ($last_name !== null) { $b->string((string)$last_name); }
        if ($about !== null) { $b->string((string)$about); }
        return Deserializer::parse($this->client->rpc($b->build()), 'User');
    }

    /**
     * account.updateStatus#6628562c = Bool.
     */
    public function updateStatus(bool $offline): mixed
    {
        $b = Builder::ctor(0x6628562C);
        $b->bool((bool)$offline);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * account.updateTheme#2bf40ccc = Theme.
     */
    public function updateTheme(string $format, string $theme, ?string $slug = null, ?string $title = null, ?string $document = null, ?array $settings = null): mixed
    {
        $flags = 0;
        if ($slug !== null) { $flags |= (1 << 0); }
        if ($title !== null) { $flags |= (1 << 1); }
        if ($document !== null) { $flags |= (1 << 2); }
        if ($settings !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x2BF40CCC);
        $b->int($flags);
        $b->string((string)$format);
        $b->rawBlob($theme);
        if ($slug !== null) { $b->string((string)$slug); }
        if ($title !== null) { $b->string((string)$title); }
        if ($document !== null) { $b->rawBlob($document); }
        if ($settings !== null) { $b->vector($settings); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Theme');
    }

    /**
     * account.updateUsername#3e0bdd7c = User.
     */
    public function updateUsername(string $username): mixed
    {
        $b = Builder::ctor(0x3E0BDD7C);
        $b->string((string)$username);
        return Deserializer::parse($this->client->rpc($b->build()), 'User');
    }

    /**
     * account.updateWebBrowserSettings#9adf82fe = account.WebBrowserSettings.
     */
    public function updateWebBrowserSettings(bool $open_external_browser = false, bool $display_close_button = false): mixed
    {
        $flags = 0;
        if ($open_external_browser) { $flags |= (1 << 0); }
        if ($display_close_button) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x9ADF82FE);
        $b->int($flags);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.WebBrowserSettings');
    }

    /**
     * account.uploadRingtone#831a83a2 = Document.
     */
    public function uploadRingtone(string $file, string $file_name, string $mime_type): mixed
    {
        $b = Builder::ctor(0x831A83A2);
        $b->rawBlob($file);
        $b->string((string)$file_name);
        $b->string((string)$mime_type);
        return Deserializer::parse($this->client->rpc($b->build()), 'Document');
    }

    /**
     * account.uploadTheme#1c3db333 = Document.
     */
    public function uploadTheme(string $file, string $file_name, string $mime_type, ?string $thumb = null): mixed
    {
        $flags = 0;
        if ($thumb !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x1C3DB333);
        $b->int($flags);
        $b->rawBlob($file);
        if ($thumb !== null) { $b->rawBlob($thumb); }
        $b->string((string)$file_name);
        $b->string((string)$mime_type);
        return Deserializer::parse($this->client->rpc($b->build()), 'Document');
    }

    /**
     * account.uploadWallPaper#e39a8f03 = WallPaper.
     */
    public function uploadWallPaper(string $file, string $mime_type, string $settings, bool $for_chat = false): mixed
    {
        $flags = 0;
        if ($for_chat) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xE39A8F03);
        $b->int($flags);
        $b->rawBlob($file);
        $b->string((string)$mime_type);
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'WallPaper');
    }

    /**
     * account.verifyEmail#32da4cf = account.EmailVerified.
     */
    public function verifyEmail(string $purpose, string $verification): mixed
    {
        $b = Builder::ctor(0x32DA4CF);
        $b->rawBlob($purpose);
        $b->rawBlob($verification);
        return Deserializer::parse($this->client->rpc($b->build()), 'account.EmailVerified');
    }

    /**
     * account.verifyPhone#4dd3a7f6 = Bool.
     */
    public function verifyPhone(string $phone_number, string $phone_code_hash, string $phone_code): mixed
    {
        $b = Builder::ctor(0x4DD3A7F6);
        $b->string((string)$phone_number);
        $b->string((string)$phone_code_hash);
        $b->string((string)$phone_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }
}
