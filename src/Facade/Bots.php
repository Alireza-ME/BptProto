<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every bots.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Bots extends Group
{

    /**
     * bots.addPreviewMedia#17aeb75a = BotPreviewMedia.
     */
    public function addPreviewMedia(mixed $bot, string $lang_code, string $media): mixed
    {
        $b = Builder::ctor(0x17AEB75A);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->string((string)$lang_code);
        $b->rawBlob($media);
        return Deserializer::parse($this->client->rpc($b->build()), 'BotPreviewMedia');
    }

    /**
     * bots.allowSendMessage#f132e3ef = Updates.
     */
    public function allowSendMessage(mixed $bot): mixed
    {
        $b = Builder::ctor(0xF132E3EF);
        $b->rawBlob($this->peers()->resolveUser($bot));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * bots.answerWebhookJSONQuery#e6213f4d = Bool.
     */
    public function answerWebhookJSONQuery(int $query_id, string $data): mixed
    {
        $b = Builder::ctor(0xE6213F4D);
        $b->long((int)$query_id);
        $b->rawBlob($data);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.canSendMessage#1359f4e6 = Bool.
     */
    public function canSendMessage(mixed $bot): mixed
    {
        $b = Builder::ctor(0x1359F4E6);
        $b->rawBlob($this->peers()->resolveUser($bot));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.checkDownloadFileParams#50077589 = Bool.
     */
    public function checkDownloadFileParams(mixed $bot, string $file_name, string $url): mixed
    {
        $b = Builder::ctor(0x50077589);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->string((string)$file_name);
        $b->string((string)$url);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.checkUsername#87f2219b = Bool.
     */
    public function checkUsername(string $username): mixed
    {
        $b = Builder::ctor(0x87F2219B);
        $b->string((string)$username);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.createBot#e5b17f2b = User.
     */
    public function createBot(string $name, string $username, mixed $manager_id, bool $via_deeplink = false): mixed
    {
        $flags = 0;
        if ($via_deeplink) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xE5B17F2B);
        $b->int($flags);
        $b->string((string)$name);
        $b->string((string)$username);
        $b->rawBlob($this->peers()->resolveUser($manager_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'User');
    }

    /**
     * bots.deletePreviewMedia#2d0135b3 = Bool.
     */
    public function deletePreviewMedia(mixed $bot, string $lang_code, array $media): mixed
    {
        $b = Builder::ctor(0x2D0135B3);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->string((string)$lang_code);
        $b->vector($media);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.editAccessSettings#31813cd8 = Bool.
     */
    public function editAccessSettings(mixed $bot, bool $restricted = false, ?array $add_users = null): mixed
    {
        $flags = 0;
        if ($restricted) { $flags |= (1 << 0); }
        if ($add_users !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x31813CD8);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveUser($bot));
        if ($add_users !== null) { $b->vector(array_map(fn($x) => $this->peers()->resolveUser($x), $add_users)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.editPreviewMedia#8525606f = BotPreviewMedia.
     */
    public function editPreviewMedia(mixed $bot, string $lang_code, string $media, string $new_media): mixed
    {
        $b = Builder::ctor(0x8525606F);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->string((string)$lang_code);
        $b->rawBlob($media);
        $b->rawBlob($new_media);
        return Deserializer::parse($this->client->rpc($b->build()), 'BotPreviewMedia');
    }

    /**
     * bots.exportBotToken#bd0d99eb = bots.ExportedBotToken.
     */
    public function exportBotToken(mixed $bot, bool $revoke): mixed
    {
        $b = Builder::ctor(0xBD0D99EB);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->bool((bool)$revoke);
        return Deserializer::parse($this->client->rpc($b->build()), 'bots.ExportedBotToken');
    }

    /**
     * bots.getAccessSettings#213853a3 = bots.AccessSettings.
     */
    public function getAccessSettings(mixed $bot): mixed
    {
        $b = Builder::ctor(0x213853A3);
        $b->rawBlob($this->peers()->resolveUser($bot));
        return Deserializer::parse($this->client->rpc($b->build()), 'bots.AccessSettings');
    }

    /**
     * bots.getAdminedBots#b0711d83 = Vector<User>.
     */
    public function getAdminedBots(): mixed
    {
        $b = Builder::ctor(0xB0711D83);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<User>');
    }

    /**
     * bots.getBotCommands#e34c0dd6 = Vector<BotCommand>.
     */
    public function getBotCommands(string $scope, string $lang_code): mixed
    {
        $b = Builder::ctor(0xE34C0DD6);
        $b->rawBlob($scope);
        $b->string((string)$lang_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<BotCommand>');
    }

    /**
     * bots.getBotInfo#dcd914fd = bots.BotInfo.
     */
    public function getBotInfo(string $lang_code, mixed $bot = null): mixed
    {
        $flags = 0;
        if ($bot !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xDCD914FD);
        $b->int($flags);
        if ($bot !== null) { $b->rawBlob($this->peers()->resolveUser($bot)); }
        $b->string((string)$lang_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'bots.BotInfo');
    }

    /**
     * bots.getBotMenuButton#9c60eb28 = BotMenuButton.
     */
    public function getBotMenuButton(mixed $user_id): mixed
    {
        $b = Builder::ctor(0x9C60EB28);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'BotMenuButton');
    }

    /**
     * bots.getBotRecommendations#a1b70815 = users.Users.
     */
    public function getBotRecommendations(mixed $bot): mixed
    {
        $b = Builder::ctor(0xA1B70815);
        $b->rawBlob($this->peers()->resolveUser($bot));
        return Deserializer::parse($this->client->rpc($b->build()), 'users.Users');
    }

    /**
     * bots.getPopularAppBots#c2510192 = bots.PopularAppBots.
     */
    public function getPopularAppBots(string $offset, int $limit): mixed
    {
        $b = Builder::ctor(0xC2510192);
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'bots.PopularAppBots');
    }

    /**
     * bots.getPreviewInfo#423ab3ad = bots.PreviewInfo.
     */
    public function getPreviewInfo(mixed $bot, string $lang_code): mixed
    {
        $b = Builder::ctor(0x423AB3AD);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->string((string)$lang_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'bots.PreviewInfo');
    }

    /**
     * bots.getPreviewMedias#a2a5594d = Vector<BotPreviewMedia>.
     */
    public function getPreviewMedias(mixed $bot): mixed
    {
        $b = Builder::ctor(0xA2A5594D);
        $b->rawBlob($this->peers()->resolveUser($bot));
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<BotPreviewMedia>');
    }

    /**
     * bots.getRequestedWebViewButton#bf25b7f3 = KeyboardButton.
     */
    public function getRequestedWebViewButton(mixed $bot, string $webapp_req_id): mixed
    {
        $b = Builder::ctor(0xBF25B7F3);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->string((string)$webapp_req_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'KeyboardButton');
    }

    /**
     * bots.invokeWebViewCustomMethod#87fc5e7 = DataJSON.
     */
    public function invokeWebViewCustomMethod(mixed $bot, string $custom_method, string $params): mixed
    {
        $b = Builder::ctor(0x87FC5E7);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->string((string)$custom_method);
        $b->rawBlob($params);
        return Deserializer::parse($this->client->rpc($b->build()), 'DataJSON');
    }

    /**
     * bots.reorderPreviewMedias#b627f3aa = Bool.
     */
    public function reorderPreviewMedias(mixed $bot, string $lang_code, array $order): mixed
    {
        $b = Builder::ctor(0xB627F3AA);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->string((string)$lang_code);
        $b->vector($order);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.reorderUsernames#9709b1c2 = Bool.
     */
    public function reorderUsernames(mixed $bot, array $order): mixed
    {
        $b = Builder::ctor(0x9709B1C2);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->vectorString($order);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.requestWebViewButton#31a2a35e = bots.RequestedButton.
     */
    public function requestWebViewButton(mixed $user_id, string $button): mixed
    {
        $b = Builder::ctor(0x31A2A35E);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->rawBlob($button);
        return Deserializer::parse($this->client->rpc($b->build()), 'bots.RequestedButton');
    }

    /**
     * bots.resetBotCommands#3d8de0f9 = Bool.
     */
    public function resetBotCommands(string $scope, string $lang_code): mixed
    {
        $b = Builder::ctor(0x3D8DE0F9);
        $b->rawBlob($scope);
        $b->string((string)$lang_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.sendCustomRequest#aa2769ed = DataJSON.
     */
    public function sendCustomRequest(string $custom_method, string $params): mixed
    {
        $b = Builder::ctor(0xAA2769ED);
        $b->string((string)$custom_method);
        $b->rawBlob($params);
        return Deserializer::parse($this->client->rpc($b->build()), 'DataJSON');
    }

    /**
     * bots.setBotBroadcastDefaultAdminRights#788464e1 = Bool.
     */
    public function setBotBroadcastDefaultAdminRights(string $admin_rights): mixed
    {
        $b = Builder::ctor(0x788464E1);
        $b->rawBlob($admin_rights);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.setBotCommands#517165a = Bool.
     */
    public function setBotCommands(string $scope, string $lang_code, array $commands): mixed
    {
        $b = Builder::ctor(0x517165A);
        $b->rawBlob($scope);
        $b->string((string)$lang_code);
        $b->vector($commands);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.setBotGroupDefaultAdminRights#925ec9ea = Bool.
     */
    public function setBotGroupDefaultAdminRights(string $admin_rights): mixed
    {
        $b = Builder::ctor(0x925EC9EA);
        $b->rawBlob($admin_rights);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.setBotInfo#10cf3123 = Bool.
     */
    public function setBotInfo(string $lang_code, mixed $bot = null, ?string $name = null, ?string $about = null, ?string $description = null): mixed
    {
        $flags = 0;
        if ($bot !== null) { $flags |= (1 << 2); }
        if ($name !== null) { $flags |= (1 << 3); }
        if ($about !== null) { $flags |= (1 << 0); }
        if ($description !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x10CF3123);
        $b->int($flags);
        if ($bot !== null) { $b->rawBlob($this->peers()->resolveUser($bot)); }
        $b->string((string)$lang_code);
        if ($name !== null) { $b->string((string)$name); }
        if ($about !== null) { $b->string((string)$about); }
        if ($description !== null) { $b->string((string)$description); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.setBotMenuButton#4504d54f = Bool.
     */
    public function setBotMenuButton(mixed $user_id, string $button): mixed
    {
        $b = Builder::ctor(0x4504D54F);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->rawBlob($button);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.setCustomVerification#8b89dfbd = Bool.
     */
    public function setCustomVerification(mixed $peer, bool $enabled = false, mixed $bot = null, ?string $custom_description = null): mixed
    {
        $flags = 0;
        if ($enabled) { $flags |= (1 << 1); }
        if ($bot !== null) { $flags |= (1 << 0); }
        if ($custom_description !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x8B89DFBD);
        $b->int($flags);
        if ($bot !== null) { $b->rawBlob($this->peers()->resolveUser($bot)); }
        $b->rawBlob($this->peerBlob($peer));
        if ($custom_description !== null) { $b->string((string)$custom_description); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.setJoinChatResults#e71a4810 = Bool.
     */
    public function setJoinChatResults(int $query_id, string $result): mixed
    {
        $b = Builder::ctor(0xE71A4810);
        $b->long((int)$query_id);
        $b->rawBlob($result);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.toggleUserEmojiStatusPermission#6de6392 = Bool.
     */
    public function toggleUserEmojiStatusPermission(mixed $bot, bool $enabled): mixed
    {
        $b = Builder::ctor(0x6DE6392);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->bool((bool)$enabled);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.toggleUsername#53ca973 = Bool.
     */
    public function toggleUsername(mixed $bot, string $username, bool $active): mixed
    {
        $b = Builder::ctor(0x53CA973);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->string((string)$username);
        $b->bool((bool)$active);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * bots.updateStarRefProgram#778b5ab3 = StarRefProgram.
     */
    public function updateStarRefProgram(mixed $bot, int $commission_permille, ?int $duration_months = null): mixed
    {
        $flags = 0;
        if ($duration_months !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x778B5AB3);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->int((int)$commission_permille);
        if ($duration_months !== null) { $b->int((int)$duration_months); }
        return Deserializer::parse($this->client->rpc($b->build()), 'StarRefProgram');
    }

    /**
     * bots.updateUserEmojiStatus#ed9f30c5 = Bool.
     */
    public function updateUserEmojiStatus(mixed $user_id, string $emoji_status): mixed
    {
        $b = Builder::ctor(0xED9F30C5);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->rawBlob($emoji_status);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }
}
