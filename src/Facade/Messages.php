<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every messages.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Messages extends Group
{

    /**
     * messages.acceptEncryption#3dbc0415 = EncryptedChat.
     */
    public function acceptEncryption(string $peer, string $g_b, int $key_fingerprint): mixed
    {
        $b = Builder::ctor(0x3DBC0415);
        $b->rawBlob($peer);
        $b->string((string)$g_b);
        $b->long((int)$key_fingerprint);
        return Deserializer::parse($this->client->rpc($b->build()), 'EncryptedChat');
    }

    /**
     * messages.acceptUrlAuth#67a3f0de = UrlAuthResult.
     */
    public function acceptUrlAuth(bool $write_allowed = false, bool $share_phone_number = false, mixed $peer = null, ?int $msg_id = null, ?int $button_id = null, ?string $url = null, ?string $match_code = null): mixed
    {
        $flags = 0;
        if ($write_allowed) { $flags |= (1 << 0); }
        if ($share_phone_number) { $flags |= (1 << 3); }
        if ($peer !== null) { $flags |= (1 << 1); }
        if ($msg_id !== null) { $flags |= (1 << 1); }
        if ($button_id !== null) { $flags |= (1 << 1); }
        if ($url !== null) { $flags |= (1 << 2); }
        if ($match_code !== null) { $flags |= (1 << 4); }
        $b = Builder::ctor(0x67A3F0DE);
        $b->int($flags);
        if ($peer !== null) { $b->rawBlob($this->peerBlob($peer)); }
        if ($msg_id !== null) { $b->int((int)$msg_id); }
        if ($button_id !== null) { $b->int((int)$button_id); }
        if ($url !== null) { $b->string((string)$url); }
        if ($match_code !== null) { $b->string((string)$match_code); }
        return Deserializer::parse($this->client->rpc($b->build()), 'UrlAuthResult');
    }

    /**
     * messages.addChatUser#cbc6d107 = messages.InvitedUsers.
     */
    public function addChatUser(int $chat_id, mixed $user_id, int $fwd_limit): mixed
    {
        $b = Builder::ctor(0xCBC6D107);
        $b->long((int)$chat_id);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->int((int)$fwd_limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.InvitedUsers');
    }

    /**
     * messages.addPollAnswer#19bc4b6d = Updates.
     */
    public function addPollAnswer(mixed $peer, int $msg_id, string $answer): mixed
    {
        $b = Builder::ctor(0x19BC4B6D);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->rawBlob($answer);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.appendTodoList#21a61057 = Updates.
     */
    public function appendTodoList(mixed $peer, int $msg_id, array $list_): mixed
    {
        $b = Builder::ctor(0x21A61057);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->vector($list_);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.checkChatInvite#3eadb1bb = ChatInvite.
     */
    public function checkChatInvite(string $hash): mixed
    {
        $b = Builder::ctor(0x3EADB1BB);
        $b->string((string)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'ChatInvite');
    }

    /**
     * messages.checkHistoryImport#43fe19f3 = messages.HistoryImportParsed.
     */
    public function checkHistoryImport(string $import_head): mixed
    {
        $b = Builder::ctor(0x43FE19F3);
        $b->string((string)$import_head);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.HistoryImportParsed');
    }

    /**
     * messages.checkHistoryImportPeer#5dc60f03 = messages.CheckedHistoryImportPeer.
     */
    public function checkHistoryImportPeer(mixed $peer): mixed
    {
        $b = Builder::ctor(0x5DC60F03);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.CheckedHistoryImportPeer');
    }

    /**
     * messages.checkQuickReplyShortcut#f1d0fbd3 = Bool.
     */
    public function checkQuickReplyShortcut(string $shortcut): mixed
    {
        $b = Builder::ctor(0xF1D0FBD3);
        $b->string((string)$shortcut);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.checkUrlAuthMatchCode#c9a47b0b = Bool.
     */
    public function checkUrlAuthMatchCode(string $url, string $match_code): mixed
    {
        $b = Builder::ctor(0xC9A47B0B);
        $b->string((string)$url);
        $b->string((string)$match_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.clearAllDrafts#7e58ee9c = Bool.
     */
    public function clearAllDrafts(): mixed
    {
        $b = Builder::ctor(0x7E58EE9C);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.clearRecentReactions#9dfeefb4 = Bool.
     */
    public function clearRecentReactions(): mixed
    {
        $b = Builder::ctor(0x9DFEEFB4);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.clearRecentStickers#8999602d = Bool.
     */
    public function clearRecentStickers(bool $attached = false): mixed
    {
        $flags = 0;
        if ($attached) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x8999602D);
        $b->int($flags);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.clickSponsoredMessage#8235057e = Bool.
     */
    public function clickSponsoredMessage(string $random_id, bool $media = false, bool $fullscreen = false): mixed
    {
        $flags = 0;
        if ($media) { $flags |= (1 << 0); }
        if ($fullscreen) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x8235057E);
        $b->int($flags);
        $b->string((string)$random_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.composeMessageWithAI#daecc589 = messages.ComposedMessageWithAI.
     */
    public function composeMessageWithAI(string $text, bool $proofread = false, bool $emojify = false, ?string $translate_to_lang = null, ?string $tone = null): mixed
    {
        $flags = 0;
        if ($proofread) { $flags |= (1 << 0); }
        if ($emojify) { $flags |= (1 << 3); }
        if ($translate_to_lang !== null) { $flags |= (1 << 1); }
        if ($tone !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xDAECC589);
        $b->int($flags);
        $b->rawBlob($text);
        if ($translate_to_lang !== null) { $b->string((string)$translate_to_lang); }
        if ($tone !== null) { $b->rawBlob($tone); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ComposedMessageWithAI');
    }

    /**
     * messages.composeRichMessageWithAI#8d7ae6af = messages.ComposedRichMessageWithAI.
     */
    public function composeRichMessageWithAI(bool $proofread = false, bool $emojify = false, ?string $text = null, ?string $translate_to_lang = null, ?string $tone = null): mixed
    {
        $flags = 0;
        if ($proofread) { $flags |= (1 << 0); }
        if ($emojify) { $flags |= (1 << 3); }
        if ($text !== null) { $flags |= (1 << 4); }
        if ($translate_to_lang !== null) { $flags |= (1 << 1); }
        if ($tone !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x8D7AE6AF);
        $b->int($flags);
        if ($text !== null) { $b->rawBlob($text); }
        if ($translate_to_lang !== null) { $b->string((string)$translate_to_lang); }
        if ($tone !== null) { $b->rawBlob($tone); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ComposedRichMessageWithAI');
    }

    /**
     * messages.createChat#92ceddd4 = messages.InvitedUsers.
     */
    public function createChat(array $users, string $title, ?int $ttl_period = null): mixed
    {
        $flags = 0;
        if ($ttl_period !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x92CEDDD4);
        $b->int($flags);
        $b->vector(array_map(fn($x) => $this->peers()->resolveUser($x), $users));
        $b->string((string)$title);
        if ($ttl_period !== null) { $b->int((int)$ttl_period); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.InvitedUsers');
    }

    /**
     * messages.createForumTopic#2f98c3d5 = Updates.
     */
    public function createForumTopic(mixed $peer, string $title, int $random_id, bool $title_missing = false, ?int $icon_color = null, ?int $icon_emoji_id = null, mixed $send_as = null): mixed
    {
        $flags = 0;
        if ($title_missing) { $flags |= (1 << 4); }
        if ($icon_color !== null) { $flags |= (1 << 0); }
        if ($icon_emoji_id !== null) { $flags |= (1 << 3); }
        if ($send_as !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x2F98C3D5);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$title);
        if ($icon_color !== null) { $b->int((int)$icon_color); }
        if ($icon_emoji_id !== null) { $b->long((int)$icon_emoji_id); }
        $b->long((int)$random_id);
        if ($send_as !== null) { $b->rawBlob($this->peerBlob($send_as)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.declineUrlAuth#35436bbc = Bool.
     */
    public function declineUrlAuth(string $url): mixed
    {
        $b = Builder::ctor(0x35436BBC);
        $b->string((string)$url);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.deleteChat#5bd0ee50 = Bool.
     */
    public function deleteChat(int $chat_id): mixed
    {
        $b = Builder::ctor(0x5BD0EE50);
        $b->long((int)$chat_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.deleteChatUser#a2185cab = Updates.
     */
    public function deleteChatUser(int $chat_id, mixed $user_id, bool $revoke_history = false): mixed
    {
        $flags = 0;
        if ($revoke_history) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xA2185CAB);
        $b->int($flags);
        $b->long((int)$chat_id);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.deleteExportedChatInvite#d464a42b = Bool.
     */
    public function deleteExportedChatInvite(mixed $peer, string $link): mixed
    {
        $b = Builder::ctor(0xD464A42B);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$link);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.deleteFactCheck#d1da940c = Updates.
     */
    public function deleteFactCheck(mixed $peer, int $msg_id): mixed
    {
        $b = Builder::ctor(0xD1DA940C);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.deleteHistory#b08f922a = messages.AffectedHistory.
     */
    public function deleteHistory(mixed $peer, int $max_id, bool $just_clear = false, bool $revoke = false, ?int $min_date = null, ?int $max_date = null): mixed
    {
        $flags = 0;
        if ($just_clear) { $flags |= (1 << 0); }
        if ($revoke) { $flags |= (1 << 1); }
        if ($min_date !== null) { $flags |= (1 << 2); }
        if ($max_date !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0xB08F922A);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$max_id);
        if ($min_date !== null) { $b->int((int)$min_date); }
        if ($max_date !== null) { $b->int((int)$max_date); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedHistory');
    }

    /**
     * messages.deleteMessages#e58e95d2 = messages.AffectedMessages.
     */
    public function deleteMessages(array $id, bool $revoke = false): mixed
    {
        $flags = 0;
        if ($revoke) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xE58E95D2);
        $b->int($flags);
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedMessages');
    }

    /**
     * messages.deleteParticipantReaction#e3b7f82c = Updates.
     */
    public function deleteParticipantReaction(mixed $peer, int $msg_id, mixed $participant): mixed
    {
        $b = Builder::ctor(0xE3B7F82C);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->rawBlob($this->peerBlob($participant));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.deleteParticipantReactions#a0b80cf8 = Bool.
     */
    public function deleteParticipantReactions(mixed $peer, mixed $participant): mixed
    {
        $b = Builder::ctor(0xA0B80CF8);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peerBlob($participant));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.deletePhoneCallHistory#f9cbe409 = messages.AffectedFoundMessages.
     */
    public function deletePhoneCallHistory(bool $revoke = false): mixed
    {
        $flags = 0;
        if ($revoke) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xF9CBE409);
        $b->int($flags);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedFoundMessages');
    }

    /**
     * messages.deletePollAnswer#ac8505a5 = Updates.
     */
    public function deletePollAnswer(mixed $peer, int $msg_id, string $option): mixed
    {
        $b = Builder::ctor(0xAC8505A5);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->string((string)$option);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.deleteQuickReplyMessages#e105e910 = Updates.
     */
    public function deleteQuickReplyMessages(int $shortcut_id, array $id): mixed
    {
        $b = Builder::ctor(0xE105E910);
        $b->int((int)$shortcut_id);
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.deleteQuickReplyShortcut#3cc04740 = Bool.
     */
    public function deleteQuickReplyShortcut(int $shortcut_id): mixed
    {
        $b = Builder::ctor(0x3CC04740);
        $b->int((int)$shortcut_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.deleteRevokedExportedChatInvites#56987bd5 = Bool.
     */
    public function deleteRevokedExportedChatInvites(mixed $peer, mixed $admin_id): mixed
    {
        $b = Builder::ctor(0x56987BD5);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peers()->resolveUser($admin_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.deleteSavedHistory#4dc5085f = messages.AffectedHistory.
     */
    public function deleteSavedHistory(mixed $peer, int $max_id, mixed $parent_peer = null, ?int $min_date = null, ?int $max_date = null): mixed
    {
        $flags = 0;
        if ($parent_peer !== null) { $flags |= (1 << 0); }
        if ($min_date !== null) { $flags |= (1 << 2); }
        if ($max_date !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x4DC5085F);
        $b->int($flags);
        if ($parent_peer !== null) { $b->rawBlob($this->peerBlob($parent_peer)); }
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$max_id);
        if ($min_date !== null) { $b->int((int)$min_date); }
        if ($max_date !== null) { $b->int((int)$max_date); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedHistory');
    }

    /**
     * messages.deleteScheduledMessages#59ae2b16 = Updates.
     */
    public function deleteScheduledMessages(mixed $peer, array $id): mixed
    {
        $b = Builder::ctor(0x59AE2B16);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.deleteTopicHistory#d2816f10 = messages.AffectedHistory.
     */
    public function deleteTopicHistory(mixed $peer, int $top_msg_id): mixed
    {
        $b = Builder::ctor(0xD2816F10);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$top_msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedHistory');
    }

    /**
     * messages.discardEncryption#f393aea0 = Bool.
     */
    public function discardEncryption(int $chat_id, bool $delete_history = false): mixed
    {
        $flags = 0;
        if ($delete_history) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xF393AEA0);
        $b->int($flags);
        $b->int((int)$chat_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.editChatAbout#def60797 = Bool.
     */
    public function editChatAbout(mixed $peer, string $about): mixed
    {
        $b = Builder::ctor(0xDEF60797);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$about);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.editChatAdmin#a85bd1c2 = Bool.
     */
    public function editChatAdmin(int $chat_id, mixed $user_id, bool $is_admin): mixed
    {
        $b = Builder::ctor(0xA85BD1C2);
        $b->long((int)$chat_id);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->bool((bool)$is_admin);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.editChatCreator#f743b857 = Updates.
     */
    public function editChatCreator(mixed $peer, mixed $user_id, string $password): mixed
    {
        $b = Builder::ctor(0xF743B857);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->rawBlob($password);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.editChatDefaultBannedRights#a5866b41 = Updates.
     */
    public function editChatDefaultBannedRights(mixed $peer, string $banned_rights): mixed
    {
        $b = Builder::ctor(0xA5866B41);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($banned_rights);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.editChatParticipantRank#a00f32b0 = Updates.
     */
    public function editChatParticipantRank(mixed $peer, mixed $participant, string $rank): mixed
    {
        $b = Builder::ctor(0xA00F32B0);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peerBlob($participant));
        $b->string((string)$rank);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.editChatPhoto#35ddd674 = Updates.
     */
    public function editChatPhoto(int $chat_id, string $photo): mixed
    {
        $b = Builder::ctor(0x35DDD674);
        $b->long((int)$chat_id);
        $b->rawBlob($photo);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.editChatTitle#73783ffd = Updates.
     */
    public function editChatTitle(int $chat_id, string $title): mixed
    {
        $b = Builder::ctor(0x73783FFD);
        $b->long((int)$chat_id);
        $b->string((string)$title);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.editExportedChatInvite#bdca2f75 = messages.ExportedChatInvite.
     */
    public function editExportedChatInvite(mixed $peer, string $link, bool $revoked = false, ?int $expire_date = null, ?int $usage_limit = null, ?bool $request_needed = null, ?string $title = null): mixed
    {
        $flags = 0;
        if ($revoked) { $flags |= (1 << 2); }
        if ($expire_date !== null) { $flags |= (1 << 0); }
        if ($usage_limit !== null) { $flags |= (1 << 1); }
        if ($request_needed !== null) { $flags |= (1 << 3); }
        if ($title !== null) { $flags |= (1 << 4); }
        $b = Builder::ctor(0xBDCA2F75);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$link);
        if ($expire_date !== null) { $b->int((int)$expire_date); }
        if ($usage_limit !== null) { $b->int((int)$usage_limit); }
        if ($request_needed !== null) { $b->bool((bool)$request_needed); }
        if ($title !== null) { $b->string((string)$title); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ExportedChatInvite');
    }

    /**
     * messages.editFactCheck#589ee75 = Updates.
     */
    public function editFactCheck(mixed $peer, int $msg_id, string $text): mixed
    {
        $b = Builder::ctor(0x589EE75);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->rawBlob($text);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.editForumTopic#cecc1134 = Updates.
     */
    public function editForumTopic(mixed $peer, int $topic_id, ?string $title = null, ?int $icon_emoji_id = null, ?bool $closed = null, ?bool $hidden = null): mixed
    {
        $flags = 0;
        if ($title !== null) { $flags |= (1 << 0); }
        if ($icon_emoji_id !== null) { $flags |= (1 << 1); }
        if ($closed !== null) { $flags |= (1 << 2); }
        if ($hidden !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0xCECC1134);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$topic_id);
        if ($title !== null) { $b->string((string)$title); }
        if ($icon_emoji_id !== null) { $b->long((int)$icon_emoji_id); }
        if ($closed !== null) { $b->bool((bool)$closed); }
        if ($hidden !== null) { $b->bool((bool)$hidden); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.editInlineBotMessage#a423bb51 = Bool.
     */
    public function editInlineBotMessage(string $id, bool $no_webpage = false, bool $invert_media = false, ?string $message = null, ?string $media = null, ?string $reply_markup = null, ?array $entities = null, ?string $rich_message = null): mixed
    {
        $flags = 0;
        if ($no_webpage) { $flags |= (1 << 1); }
        if ($invert_media) { $flags |= (1 << 16); }
        if ($message !== null) { $flags |= (1 << 11); }
        if ($media !== null) { $flags |= (1 << 14); }
        if ($reply_markup !== null) { $flags |= (1 << 2); }
        if ($entities !== null) { $flags |= (1 << 3); }
        if ($rich_message !== null) { $flags |= (1 << 23); }
        $b = Builder::ctor(0xA423BB51);
        $b->int($flags);
        $b->rawBlob($id);
        if ($message !== null) { $b->string((string)$message); }
        if ($media !== null) { $b->rawBlob($media); }
        if ($reply_markup !== null) { $b->rawBlob($reply_markup); }
        if ($entities !== null) { $b->vector($entities); }
        if ($rich_message !== null) { $b->rawBlob($rich_message); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.editMessage#b106e66c = Updates.
     */
    public function editMessage(mixed $peer, int $id, bool $no_webpage = false, bool $invert_media = false, ?string $message = null, ?string $media = null, ?string $reply_markup = null, ?array $entities = null, ?int $schedule_date = null, ?int $schedule_repeat_period = null, ?int $quick_reply_shortcut_id = null, ?string $rich_message = null): mixed
    {
        $flags = 0;
        if ($no_webpage) { $flags |= (1 << 1); }
        if ($invert_media) { $flags |= (1 << 16); }
        if ($message !== null) { $flags |= (1 << 11); }
        if ($media !== null) { $flags |= (1 << 14); }
        if ($reply_markup !== null) { $flags |= (1 << 2); }
        if ($entities !== null) { $flags |= (1 << 3); }
        if ($schedule_date !== null) { $flags |= (1 << 15); }
        if ($schedule_repeat_period !== null) { $flags |= (1 << 18); }
        if ($quick_reply_shortcut_id !== null) { $flags |= (1 << 17); }
        if ($rich_message !== null) { $flags |= (1 << 23); }
        $b = Builder::ctor(0xB106E66C);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        if ($message !== null) { $b->string((string)$message); }
        if ($media !== null) { $b->rawBlob($media); }
        if ($reply_markup !== null) { $b->rawBlob($reply_markup); }
        if ($entities !== null) { $b->vector($entities); }
        if ($schedule_date !== null) { $b->int((int)$schedule_date); }
        if ($schedule_repeat_period !== null) { $b->int((int)$schedule_repeat_period); }
        if ($quick_reply_shortcut_id !== null) { $b->int((int)$quick_reply_shortcut_id); }
        if ($rich_message !== null) { $b->rawBlob($rich_message); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.editQuickReplyShortcut#5c003cef = Bool.
     */
    public function editQuickReplyShortcut(int $shortcut_id, string $shortcut): mixed
    {
        $b = Builder::ctor(0x5C003CEF);
        $b->int((int)$shortcut_id);
        $b->string((string)$shortcut);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.exportChatInvite#a455de90 = ExportedChatInvite.
     */
    public function exportChatInvite(mixed $peer, bool $legacy_revoke_permanent = false, bool $request_needed = false, ?int $expire_date = null, ?int $usage_limit = null, ?string $title = null, ?string $subscription_pricing = null): mixed
    {
        $flags = 0;
        if ($legacy_revoke_permanent) { $flags |= (1 << 2); }
        if ($request_needed) { $flags |= (1 << 3); }
        if ($expire_date !== null) { $flags |= (1 << 0); }
        if ($usage_limit !== null) { $flags |= (1 << 1); }
        if ($title !== null) { $flags |= (1 << 4); }
        if ($subscription_pricing !== null) { $flags |= (1 << 5); }
        $b = Builder::ctor(0xA455DE90);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($expire_date !== null) { $b->int((int)$expire_date); }
        if ($usage_limit !== null) { $b->int((int)$usage_limit); }
        if ($title !== null) { $b->string((string)$title); }
        if ($subscription_pricing !== null) { $b->rawBlob($subscription_pricing); }
        return Deserializer::parse($this->client->rpc($b->build()), 'ExportedChatInvite');
    }

    /**
     * messages.faveSticker#b9ffc55b = Bool.
     */
    public function faveSticker(string $id, bool $unfave): mixed
    {
        $b = Builder::ctor(0xB9FFC55B);
        $b->rawBlob($id);
        $b->bool((bool)$unfave);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.forwardMessages#13704a7c = Updates.
     */
    public function forwardMessages(mixed $from_peer, array $id, array $random_id, mixed $to_peer, bool $silent = false, bool $background = false, bool $with_my_score = false, bool $drop_author = false, bool $drop_media_captions = false, bool $noforwards = false, bool $allow_paid_floodskip = false, bool $from_ephemeral = false, ?int $top_msg_id = null, ?string $reply_to = null, ?int $schedule_date = null, ?int $schedule_repeat_period = null, mixed $send_as = null, ?string $quick_reply_shortcut = null, ?int $effect = null, ?int $video_timestamp = null, ?int $allow_paid_stars = null, ?string $suggested_post = null): mixed
    {
        $flags = 0;
        if ($silent) { $flags |= (1 << 5); }
        if ($background) { $flags |= (1 << 6); }
        if ($with_my_score) { $flags |= (1 << 8); }
        if ($drop_author) { $flags |= (1 << 11); }
        if ($drop_media_captions) { $flags |= (1 << 12); }
        if ($noforwards) { $flags |= (1 << 14); }
        if ($allow_paid_floodskip) { $flags |= (1 << 19); }
        if ($from_ephemeral) { $flags |= (1 << 25); }
        if ($top_msg_id !== null) { $flags |= (1 << 9); }
        if ($reply_to !== null) { $flags |= (1 << 22); }
        if ($schedule_date !== null) { $flags |= (1 << 10); }
        if ($schedule_repeat_period !== null) { $flags |= (1 << 24); }
        if ($send_as !== null) { $flags |= (1 << 13); }
        if ($quick_reply_shortcut !== null) { $flags |= (1 << 17); }
        if ($effect !== null) { $flags |= (1 << 18); }
        if ($video_timestamp !== null) { $flags |= (1 << 20); }
        if ($allow_paid_stars !== null) { $flags |= (1 << 21); }
        if ($suggested_post !== null) { $flags |= (1 << 23); }
        $b = Builder::ctor(0x13704A7C);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($from_peer));
        $b->vectorInt($id);
        $b->vectorLong($random_id);
        $b->rawBlob($this->peerBlob($to_peer));
        if ($top_msg_id !== null) { $b->int((int)$top_msg_id); }
        if ($reply_to !== null) { $b->rawBlob($reply_to); }
        if ($schedule_date !== null) { $b->int((int)$schedule_date); }
        if ($schedule_repeat_period !== null) { $b->int((int)$schedule_repeat_period); }
        if ($send_as !== null) { $b->rawBlob($this->peerBlob($send_as)); }
        if ($quick_reply_shortcut !== null) { $b->rawBlob($quick_reply_shortcut); }
        if ($effect !== null) { $b->long((int)$effect); }
        if ($video_timestamp !== null) { $b->int((int)$video_timestamp); }
        if ($allow_paid_stars !== null) { $b->long((int)$allow_paid_stars); }
        if ($suggested_post !== null) { $b->rawBlob($suggested_post); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.getAdminsWithInvites#3920e6ef = messages.ChatAdminsWithInvites.
     */
    public function getAdminsWithInvites(mixed $peer): mixed
    {
        $b = Builder::ctor(0x3920E6EF);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ChatAdminsWithInvites');
    }

    /**
     * messages.getAllDrafts#6a3f8d65 = Updates.
     */
    public function getAllDrafts(): mixed
    {
        $b = Builder::ctor(0x6A3F8D65);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.getAllStickers#b8a0a1a8 = messages.AllStickers.
     */
    public function getAllStickers(int $hash): mixed
    {
        $b = Builder::ctor(0xB8A0A1A8);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AllStickers');
    }

    /**
     * messages.getArchivedStickers#57f17692 = messages.ArchivedStickers.
     */
    public function getArchivedStickers(int $offset_id, int $limit, bool $masks = false, bool $emojis = false): mixed
    {
        $flags = 0;
        if ($masks) { $flags |= (1 << 0); }
        if ($emojis) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x57F17692);
        $b->int($flags);
        $b->long((int)$offset_id);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ArchivedStickers');
    }

    /**
     * messages.getAttachMenuBot#77216192 = AttachMenuBotsBot.
     */
    public function getAttachMenuBot(mixed $bot): mixed
    {
        $b = Builder::ctor(0x77216192);
        $b->rawBlob($this->peers()->resolveUser($bot));
        return Deserializer::parse($this->client->rpc($b->build()), 'AttachMenuBotsBot');
    }

    /**
     * messages.getAttachMenuBots#16fcc2cb = AttachMenuBots.
     */
    public function getAttachMenuBots(int $hash): mixed
    {
        $b = Builder::ctor(0x16FCC2CB);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'AttachMenuBots');
    }

    /**
     * messages.getAttachedStickers#cc5b67cc = Vector<StickerSetCovered>.
     */
    public function getAttachedStickers(string $media): mixed
    {
        $b = Builder::ctor(0xCC5B67CC);
        $b->rawBlob($media);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<StickerSetCovered>');
    }

    /**
     * messages.getAvailableEffects#dea20a39 = messages.AvailableEffects.
     */
    public function getAvailableEffects(int $hash): mixed
    {
        $b = Builder::ctor(0xDEA20A39);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AvailableEffects');
    }

    /**
     * messages.getAvailableReactions#18dea0ac = messages.AvailableReactions.
     */
    public function getAvailableReactions(int $hash): mixed
    {
        $b = Builder::ctor(0x18DEA0AC);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AvailableReactions');
    }

    /**
     * messages.getBotApp#34fdc5c3 = messages.BotApp.
     */
    public function getBotApp(string $app, int $hash): mixed
    {
        $b = Builder::ctor(0x34FDC5C3);
        $b->rawBlob($app);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.BotApp');
    }

    /**
     * messages.getBotCallbackAnswer#9342ca07 = messages.BotCallbackAnswer.
     */
    public function getBotCallbackAnswer(mixed $peer, int $msg_id, bool $game = false, ?string $data = null, ?string $password = null): mixed
    {
        $flags = 0;
        if ($game) { $flags |= (1 << 1); }
        if ($data !== null) { $flags |= (1 << 0); }
        if ($password !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x9342CA07);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        if ($data !== null) { $b->string((string)$data); }
        if ($password !== null) { $b->rawBlob($password); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.BotCallbackAnswer');
    }

    /**
     * messages.getChatInviteImporters#df04dd4e = messages.ChatInviteImporters.
     */
    public function getChatInviteImporters(mixed $peer, int $offset_date, mixed $offset_user, int $limit, bool $requested = false, bool $subscription_expired = false, ?string $link = null, ?string $q = null): mixed
    {
        $flags = 0;
        if ($requested) { $flags |= (1 << 0); }
        if ($subscription_expired) { $flags |= (1 << 3); }
        if ($link !== null) { $flags |= (1 << 1); }
        if ($q !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xDF04DD4E);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($link !== null) { $b->string((string)$link); }
        if ($q !== null) { $b->string((string)$q); }
        $b->int((int)$offset_date);
        $b->rawBlob($this->peers()->resolveUser($offset_user));
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ChatInviteImporters');
    }

    /**
     * messages.getChats#49e9528f = messages.Chats.
     */
    public function getChats(array $id): mixed
    {
        $b = Builder::ctor(0x49E9528F);
        $b->vectorLong($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Chats');
    }

    /**
     * messages.getCommonChats#e40ca104 = messages.Chats.
     */
    public function getCommonChats(mixed $user_id, int $max_id, int $limit): mixed
    {
        $b = Builder::ctor(0xE40CA104);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->long((int)$max_id);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Chats');
    }

    /**
     * messages.getCustomEmojiDocuments#d9ab0f54 = Vector<Document>.
     */
    public function getCustomEmojiDocuments(array $document_id): mixed
    {
        $b = Builder::ctor(0xD9AB0F54);
        $b->vectorLong($document_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<Document>');
    }

    /**
     * messages.getDefaultHistoryTTL#658b7188 = DefaultHistoryTTL.
     */
    public function getDefaultHistoryTTL(): mixed
    {
        $b = Builder::ctor(0x658B7188);
        return Deserializer::parse($this->client->rpc($b->build()), 'DefaultHistoryTTL');
    }

    /**
     * messages.getDefaultTagReactions#bdf93428 = messages.Reactions.
     */
    public function getDefaultTagReactions(int $hash): mixed
    {
        $b = Builder::ctor(0xBDF93428);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Reactions');
    }

    /**
     * messages.getDhConfig#26cf8950 = messages.DhConfig.
     */
    public function getDhConfig(int $version, int $random_length): mixed
    {
        $b = Builder::ctor(0x26CF8950);
        $b->int((int)$version);
        $b->int((int)$random_length);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.DhConfig');
    }

    /**
     * messages.getDialogFilters#efd48c89 = messages.DialogFilters.
     */
    public function getDialogFilters(): mixed
    {
        $b = Builder::ctor(0xEFD48C89);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.DialogFilters');
    }

    /**
     * messages.getDialogUnreadMarks#21202222 = Vector<DialogPeer>.
     */
    public function getDialogUnreadMarks(mixed $parent_peer = null): mixed
    {
        $flags = 0;
        if ($parent_peer !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x21202222);
        $b->int($flags);
        if ($parent_peer !== null) { $b->rawBlob($this->peerBlob($parent_peer)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<DialogPeer>');
    }

    /**
     * messages.getDialogs#a0f4cb4f = messages.Dialogs.
     */
    public function getDialogs(int $offset_date, int $offset_id, mixed $offset_peer, int $limit, int $hash, bool $exclude_pinned = false, ?int $folder_id = null): mixed
    {
        $flags = 0;
        if ($exclude_pinned) { $flags |= (1 << 0); }
        if ($folder_id !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xA0F4CB4F);
        $b->int($flags);
        if ($folder_id !== null) { $b->int((int)$folder_id); }
        $b->int((int)$offset_date);
        $b->int((int)$offset_id);
        $b->rawBlob($this->peerBlob($offset_peer));
        $b->int((int)$limit);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Dialogs');
    }

    /**
     * messages.getDiscussionMessage#446972fd = messages.DiscussionMessage.
     */
    public function getDiscussionMessage(mixed $peer, int $msg_id): mixed
    {
        $b = Builder::ctor(0x446972FD);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.DiscussionMessage');
    }

    /**
     * messages.getDocumentByHash#b1f2061f = Document.
     */
    public function getDocumentByHash(string $sha256, int $size, string $mime_type): mixed
    {
        $b = Builder::ctor(0xB1F2061F);
        $b->string((string)$sha256);
        $b->long((int)$size);
        $b->string((string)$mime_type);
        return Deserializer::parse($this->client->rpc($b->build()), 'Document');
    }

    /**
     * messages.getEmojiGameInfo#fb7e8ca7 = messages.EmojiGameInfo.
     */
    public function getEmojiGameInfo(): mixed
    {
        $b = Builder::ctor(0xFB7E8CA7);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.EmojiGameInfo');
    }

    /**
     * messages.getEmojiGroups#7488ce5b = messages.EmojiGroups.
     */
    public function getEmojiGroups(int $hash): mixed
    {
        $b = Builder::ctor(0x7488CE5B);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.EmojiGroups');
    }

    /**
     * messages.getEmojiKeywords#35a0e062 = EmojiKeywordsDifference.
     */
    public function getEmojiKeywords(string $lang_code): mixed
    {
        $b = Builder::ctor(0x35A0E062);
        $b->string((string)$lang_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'EmojiKeywordsDifference');
    }

    /**
     * messages.getEmojiKeywordsDifference#1508b6af = EmojiKeywordsDifference.
     */
    public function getEmojiKeywordsDifference(string $lang_code, int $from_version): mixed
    {
        $b = Builder::ctor(0x1508B6AF);
        $b->string((string)$lang_code);
        $b->int((int)$from_version);
        return Deserializer::parse($this->client->rpc($b->build()), 'EmojiKeywordsDifference');
    }

    /**
     * messages.getEmojiKeywordsLanguages#4e9963b2 = Vector<EmojiLanguage>.
     */
    public function getEmojiKeywordsLanguages(array $lang_codes): mixed
    {
        $b = Builder::ctor(0x4E9963B2);
        $b->vectorString($lang_codes);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<EmojiLanguage>');
    }

    /**
     * messages.getEmojiProfilePhotoGroups#21a548f3 = messages.EmojiGroups.
     */
    public function getEmojiProfilePhotoGroups(int $hash): mixed
    {
        $b = Builder::ctor(0x21A548F3);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.EmojiGroups');
    }

    /**
     * messages.getEmojiStatusGroups#2ecd56cd = messages.EmojiGroups.
     */
    public function getEmojiStatusGroups(int $hash): mixed
    {
        $b = Builder::ctor(0x2ECD56CD);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.EmojiGroups');
    }

    /**
     * messages.getEmojiStickerGroups#1dd840f5 = messages.EmojiGroups.
     */
    public function getEmojiStickerGroups(int $hash): mixed
    {
        $b = Builder::ctor(0x1DD840F5);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.EmojiGroups');
    }

    /**
     * messages.getEmojiStickers#fbfca18f = messages.AllStickers.
     */
    public function getEmojiStickers(int $hash): mixed
    {
        $b = Builder::ctor(0xFBFCA18F);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AllStickers');
    }

    /**
     * messages.getEmojiURL#d5b10c26 = EmojiURL.
     */
    public function getEmojiURL(string $lang_code): mixed
    {
        $b = Builder::ctor(0xD5B10C26);
        $b->string((string)$lang_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'EmojiURL');
    }

    /**
     * messages.getExportedChatInvite#73746f5c = messages.ExportedChatInvite.
     */
    public function getExportedChatInvite(mixed $peer, string $link): mixed
    {
        $b = Builder::ctor(0x73746F5C);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$link);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ExportedChatInvite');
    }

    /**
     * messages.getExportedChatInvites#a2b5a3f6 = messages.ExportedChatInvites.
     */
    public function getExportedChatInvites(mixed $peer, mixed $admin_id, int $limit, bool $revoked = false, ?int $offset_date = null, ?string $offset_link = null): mixed
    {
        $flags = 0;
        if ($revoked) { $flags |= (1 << 3); }
        if ($offset_date !== null) { $flags |= (1 << 2); }
        if ($offset_link !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xA2B5A3F6);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peers()->resolveUser($admin_id));
        if ($offset_date !== null) { $b->int((int)$offset_date); }
        if ($offset_link !== null) { $b->string((string)$offset_link); }
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ExportedChatInvites');
    }

    /**
     * messages.getExtendedMedia#84f80814 = Updates.
     */
    public function getExtendedMedia(mixed $peer, array $id): mixed
    {
        $b = Builder::ctor(0x84F80814);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.getFactCheck#b9cdc5ee = Vector<FactCheck>.
     */
    public function getFactCheck(mixed $peer, array $msg_id): mixed
    {
        $b = Builder::ctor(0xB9CDC5EE);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<FactCheck>');
    }

    /**
     * messages.getFavedStickers#4f1aaa9 = messages.FavedStickers.
     */
    public function getFavedStickers(int $hash): mixed
    {
        $b = Builder::ctor(0x4F1AAA9);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.FavedStickers');
    }

    /**
     * messages.getFeaturedEmojiStickers#ecf6736 = messages.FeaturedStickers.
     */
    public function getFeaturedEmojiStickers(int $hash): mixed
    {
        $b = Builder::ctor(0xECF6736);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.FeaturedStickers');
    }

    /**
     * messages.getFeaturedStickers#64780b14 = messages.FeaturedStickers.
     */
    public function getFeaturedStickers(int $hash): mixed
    {
        $b = Builder::ctor(0x64780B14);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.FeaturedStickers');
    }

    /**
     * messages.getForumTopics#3ba47bff = messages.ForumTopics.
     */
    public function getForumTopics(mixed $peer, int $offset_date, int $offset_id, int $offset_topic, int $limit, ?string $q = null): mixed
    {
        $flags = 0;
        if ($q !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x3BA47BFF);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($q !== null) { $b->string((string)$q); }
        $b->int((int)$offset_date);
        $b->int((int)$offset_id);
        $b->int((int)$offset_topic);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ForumTopics');
    }

    /**
     * messages.getForumTopicsByID#af0a4a08 = messages.ForumTopics.
     */
    public function getForumTopicsByID(mixed $peer, array $topics): mixed
    {
        $b = Builder::ctor(0xAF0A4A08);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($topics);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ForumTopics');
    }

    /**
     * messages.getFullChat#aeb00b34 = messages.ChatFull.
     */
    public function getFullChat(int $chat_id): mixed
    {
        $b = Builder::ctor(0xAEB00B34);
        $b->long((int)$chat_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ChatFull');
    }

    /**
     * messages.getFutureChatCreatorAfterLeave#3b7d0ea6 = User.
     */
    public function getFutureChatCreatorAfterLeave(mixed $peer): mixed
    {
        $b = Builder::ctor(0x3B7D0EA6);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'User');
    }

    /**
     * messages.getGameHighScores#e822649d = messages.HighScores.
     */
    public function getGameHighScores(mixed $peer, int $id, mixed $user_id): mixed
    {
        $b = Builder::ctor(0xE822649D);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.HighScores');
    }

    /**
     * messages.getHistory#4423e6c5 = messages.Messages.
     */
    public function getHistory(mixed $peer, int $offset_id, int $offset_date, int $add_offset, int $limit, int $max_id, int $min_id, int $hash): mixed
    {
        $b = Builder::ctor(0x4423E6C5);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$offset_id);
        $b->int((int)$offset_date);
        $b->int((int)$add_offset);
        $b->int((int)$limit);
        $b->int((int)$max_id);
        $b->int((int)$min_id);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getInlineBotResults#514e999d = messages.BotResults.
     */
    public function getInlineBotResults(mixed $bot, mixed $peer, string $query, string $offset, ?string $geo_point = null): mixed
    {
        $flags = 0;
        if ($geo_point !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x514E999D);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->rawBlob($this->peerBlob($peer));
        if ($geo_point !== null) { $b->rawBlob($geo_point); }
        $b->string((string)$query);
        $b->string((string)$offset);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.BotResults');
    }

    /**
     * messages.getInlineGameHighScores#f635e1b = messages.HighScores.
     */
    public function getInlineGameHighScores(string $id, mixed $user_id): mixed
    {
        $b = Builder::ctor(0xF635E1B);
        $b->rawBlob($id);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.HighScores');
    }

    /**
     * messages.getMaskStickers#640f82b8 = messages.AllStickers.
     */
    public function getMaskStickers(int $hash): mixed
    {
        $b = Builder::ctor(0x640F82B8);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AllStickers');
    }

    /**
     * messages.getMessageEditData#fda68d36 = messages.MessageEditData.
     */
    public function getMessageEditData(mixed $peer, int $id): mixed
    {
        $b = Builder::ctor(0xFDA68D36);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.MessageEditData');
    }

    /**
     * messages.getMessageReactionsList#461b3f48 = messages.MessageReactionsList.
     */
    public function getMessageReactionsList(mixed $peer, int $id, int $limit, ?string $reaction = null, ?string $offset = null): mixed
    {
        $flags = 0;
        if ($reaction !== null) { $flags |= (1 << 0); }
        if ($offset !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x461B3F48);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        if ($reaction !== null) { $b->rawBlob($reaction); }
        if ($offset !== null) { $b->string((string)$offset); }
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.MessageReactionsList');
    }

    /**
     * messages.getMessageReadParticipants#31c1c44f = Vector<ReadParticipantDate>.
     */
    public function getMessageReadParticipants(mixed $peer, int $msg_id): mixed
    {
        $b = Builder::ctor(0x31C1C44F);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<ReadParticipantDate>');
    }

    /**
     * messages.getMessages#63c66506 = messages.Messages.
     */
    public function getMessages(array $id): mixed
    {
        $b = Builder::ctor(0x63C66506);
        $b->vector($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getMessagesReactions#8bba90e6 = Updates.
     */
    public function getMessagesReactions(mixed $peer, array $id): mixed
    {
        $b = Builder::ctor(0x8BBA90E6);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.getMessagesViews#5784d3e1 = messages.MessageViews.
     */
    public function getMessagesViews(mixed $peer, array $id, bool $increment): mixed
    {
        $b = Builder::ctor(0x5784D3E1);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        $b->bool((bool)$increment);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.MessageViews');
    }

    /**
     * messages.getMyStickers#d0b5e1fc = messages.MyStickers.
     */
    public function getMyStickers(int $offset_id, int $limit): mixed
    {
        $b = Builder::ctor(0xD0B5E1FC);
        $b->long((int)$offset_id);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.MyStickers');
    }

    /**
     * messages.getOldFeaturedStickers#7ed094a1 = messages.FeaturedStickers.
     */
    public function getOldFeaturedStickers(int $offset, int $limit, int $hash): mixed
    {
        $b = Builder::ctor(0x7ED094A1);
        $b->int((int)$offset);
        $b->int((int)$limit);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.FeaturedStickers');
    }

    /**
     * messages.getOnlines#6e2be050 = ChatOnlines.
     */
    public function getOnlines(mixed $peer): mixed
    {
        $b = Builder::ctor(0x6E2BE050);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'ChatOnlines');
    }

    /**
     * messages.getOutboxReadDate#8c4bfe5d = OutboxReadDate.
     */
    public function getOutboxReadDate(mixed $peer, int $msg_id): mixed
    {
        $b = Builder::ctor(0x8C4BFE5D);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'OutboxReadDate');
    }

    /**
     * messages.getPaidReactionPrivacy#472455aa = Updates.
     */
    public function getPaidReactionPrivacy(): mixed
    {
        $b = Builder::ctor(0x472455AA);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.getPeerDialogs#e470bcfd = messages.PeerDialogs.
     */
    public function getPeerDialogs(array $peers): mixed
    {
        $b = Builder::ctor(0xE470BCFD);
        $b->vector(array_map(fn($x) => $this->peers()->resolveDialogPeer($x), $peers));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.PeerDialogs');
    }

    /**
     * messages.getPeerSettings#efd9a6a2 = messages.PeerSettings.
     */
    public function getPeerSettings(mixed $peer): mixed
    {
        $b = Builder::ctor(0xEFD9A6A2);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.PeerSettings');
    }

    /**
     * messages.getPersonalChannelHistory#55fb0996 = messages.Messages.
     */
    public function getPersonalChannelHistory(mixed $user_id, int $limit, int $max_id, int $min_id, int $hash): mixed
    {
        $b = Builder::ctor(0x55FB0996);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->int((int)$limit);
        $b->int((int)$max_id);
        $b->int((int)$min_id);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getPinnedDialogs#d6b94df2 = messages.PeerDialogs.
     */
    public function getPinnedDialogs(int $folder_id): mixed
    {
        $b = Builder::ctor(0xD6B94DF2);
        $b->int((int)$folder_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.PeerDialogs');
    }

    /**
     * messages.getPinnedSavedDialogs#d63d94e0 = messages.SavedDialogs.
     */
    public function getPinnedSavedDialogs(): mixed
    {
        $b = Builder::ctor(0xD63D94E0);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.SavedDialogs');
    }

    /**
     * messages.getPollResults#eda3e33b = Updates.
     */
    public function getPollResults(mixed $peer, int $msg_id, int $poll_hash): mixed
    {
        $b = Builder::ctor(0xEDA3E33B);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->long((int)$poll_hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.getPollVotes#b86e380e = messages.VotesList.
     */
    public function getPollVotes(mixed $peer, int $id, int $limit, ?string $option = null, ?string $offset = null): mixed
    {
        $flags = 0;
        if ($option !== null) { $flags |= (1 << 0); }
        if ($offset !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xB86E380E);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        if ($option !== null) { $b->string((string)$option); }
        if ($offset !== null) { $b->string((string)$offset); }
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.VotesList');
    }

    /**
     * messages.getPreparedInlineMessage#857ebdb8 = messages.PreparedInlineMessage.
     */
    public function getPreparedInlineMessage(mixed $bot, string $id): mixed
    {
        $b = Builder::ctor(0x857EBDB8);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->string((string)$id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.PreparedInlineMessage');
    }

    /**
     * messages.getQuickReplies#d483f2a8 = messages.QuickReplies.
     */
    public function getQuickReplies(int $hash): mixed
    {
        $b = Builder::ctor(0xD483F2A8);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.QuickReplies');
    }

    /**
     * messages.getQuickReplyMessages#94a495c3 = messages.Messages.
     */
    public function getQuickReplyMessages(int $shortcut_id, int $hash, ?array $id = null): mixed
    {
        $flags = 0;
        if ($id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x94A495C3);
        $b->int($flags);
        $b->int((int)$shortcut_id);
        if ($id !== null) { $b->vectorInt($id); }
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getRecentLocations#702a40e0 = messages.Messages.
     */
    public function getRecentLocations(mixed $peer, int $limit, int $hash): mixed
    {
        $b = Builder::ctor(0x702A40E0);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$limit);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getRecentReactions#39461db2 = messages.Reactions.
     */
    public function getRecentReactions(int $limit, int $hash): mixed
    {
        $b = Builder::ctor(0x39461DB2);
        $b->int((int)$limit);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Reactions');
    }

    /**
     * messages.getRecentStickers#9da9403b = messages.RecentStickers.
     */
    public function getRecentStickers(int $hash, bool $attached = false): mixed
    {
        $flags = 0;
        if ($attached) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x9DA9403B);
        $b->int($flags);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.RecentStickers');
    }

    /**
     * messages.getReplies#22ddd30c = messages.Messages.
     */
    public function getReplies(mixed $peer, int $msg_id, int $offset_id, int $offset_date, int $add_offset, int $limit, int $max_id, int $min_id, int $hash): mixed
    {
        $b = Builder::ctor(0x22DDD30C);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->int((int)$offset_id);
        $b->int((int)$offset_date);
        $b->int((int)$add_offset);
        $b->int((int)$limit);
        $b->int((int)$max_id);
        $b->int((int)$min_id);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getRichMessage#501569cf = messages.Messages.
     */
    public function getRichMessage(mixed $peer, int $id): mixed
    {
        $b = Builder::ctor(0x501569CF);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getSavedDialogs#1e91fc99 = messages.SavedDialogs.
     */
    public function getSavedDialogs(int $offset_date, int $offset_id, mixed $offset_peer, int $limit, int $hash, bool $exclude_pinned = false, mixed $parent_peer = null): mixed
    {
        $flags = 0;
        if ($exclude_pinned) { $flags |= (1 << 0); }
        if ($parent_peer !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x1E91FC99);
        $b->int($flags);
        if ($parent_peer !== null) { $b->rawBlob($this->peerBlob($parent_peer)); }
        $b->int((int)$offset_date);
        $b->int((int)$offset_id);
        $b->rawBlob($this->peerBlob($offset_peer));
        $b->int((int)$limit);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.SavedDialogs');
    }

    /**
     * messages.getSavedDialogsByID#6f6f9c96 = messages.SavedDialogs.
     */
    public function getSavedDialogsByID(array $ids, mixed $parent_peer = null): mixed
    {
        $flags = 0;
        if ($parent_peer !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x6F6F9C96);
        $b->int($flags);
        if ($parent_peer !== null) { $b->rawBlob($this->peerBlob($parent_peer)); }
        $b->vector(array_map(fn($x) => $this->peerBlob($x), $ids));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.SavedDialogs');
    }

    /**
     * messages.getSavedGifs#5cf09635 = messages.SavedGifs.
     */
    public function getSavedGifs(int $hash): mixed
    {
        $b = Builder::ctor(0x5CF09635);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.SavedGifs');
    }

    /**
     * messages.getSavedHistory#998ab009 = messages.Messages.
     */
    public function getSavedHistory(mixed $peer, int $offset_id, int $offset_date, int $add_offset, int $limit, int $max_id, int $min_id, int $hash, mixed $parent_peer = null): mixed
    {
        $flags = 0;
        if ($parent_peer !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x998AB009);
        $b->int($flags);
        if ($parent_peer !== null) { $b->rawBlob($this->peerBlob($parent_peer)); }
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$offset_id);
        $b->int((int)$offset_date);
        $b->int((int)$add_offset);
        $b->int((int)$limit);
        $b->int((int)$max_id);
        $b->int((int)$min_id);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getSavedReactionTags#3637e05b = messages.SavedReactionTags.
     */
    public function getSavedReactionTags(int $hash, mixed $peer = null): mixed
    {
        $flags = 0;
        if ($peer !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x3637E05B);
        $b->int($flags);
        if ($peer !== null) { $b->rawBlob($this->peerBlob($peer)); }
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.SavedReactionTags');
    }

    /**
     * messages.getScheduledHistory#f516760b = messages.Messages.
     */
    public function getScheduledHistory(mixed $peer, int $hash): mixed
    {
        $b = Builder::ctor(0xF516760B);
        $b->rawBlob($this->peerBlob($peer));
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getScheduledMessages#bdbb0464 = messages.Messages.
     */
    public function getScheduledMessages(mixed $peer, array $id): mixed
    {
        $b = Builder::ctor(0xBDBB0464);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getSearchCounters#1bbcf300 = Vector<messages.SearchCounter>.
     */
    public function getSearchCounters(mixed $peer, array $filters, mixed $saved_peer_id = null, ?int $top_msg_id = null): mixed
    {
        $flags = 0;
        if ($saved_peer_id !== null) { $flags |= (1 << 2); }
        if ($top_msg_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x1BBCF300);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($saved_peer_id !== null) { $b->rawBlob($this->peerBlob($saved_peer_id)); }
        if ($top_msg_id !== null) { $b->int((int)$top_msg_id); }
        $b->vector($filters);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<messages.SearchCounter>');
    }

    /**
     * messages.getSearchResultsCalendar#6aa3f6bd = messages.SearchResultsCalendar.
     */
    public function getSearchResultsCalendar(mixed $peer, string $filter, int $offset_id, int $offset_date, mixed $saved_peer_id = null): mixed
    {
        $flags = 0;
        if ($saved_peer_id !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x6AA3F6BD);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($saved_peer_id !== null) { $b->rawBlob($this->peerBlob($saved_peer_id)); }
        $b->rawBlob($filter);
        $b->int((int)$offset_id);
        $b->int((int)$offset_date);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.SearchResultsCalendar');
    }

    /**
     * messages.getSearchResultsPositions#9c7f2f10 = messages.SearchResultsPositions.
     */
    public function getSearchResultsPositions(mixed $peer, string $filter, int $offset_id, int $limit, mixed $saved_peer_id = null): mixed
    {
        $flags = 0;
        if ($saved_peer_id !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x9C7F2F10);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($saved_peer_id !== null) { $b->rawBlob($this->peerBlob($saved_peer_id)); }
        $b->rawBlob($filter);
        $b->int((int)$offset_id);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.SearchResultsPositions');
    }

    /**
     * messages.getSplitRanges#1cff7e08 = Vector<MessageRange>.
     */
    public function getSplitRanges(): mixed
    {
        $b = Builder::ctor(0x1CFF7E08);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<MessageRange>');
    }

    /**
     * messages.getSponsoredMessages#3d6ce850 = messages.SponsoredMessages.
     */
    public function getSponsoredMessages(mixed $peer, ?int $msg_id = null): mixed
    {
        $flags = 0;
        if ($msg_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x3D6CE850);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($msg_id !== null) { $b->int((int)$msg_id); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.SponsoredMessages');
    }

    /**
     * messages.getStickerSet#c8a0ec74 = messages.StickerSet.
     */
    public function getStickerSet(string $stickerset, int $hash): mixed
    {
        $b = Builder::ctor(0xC8A0EC74);
        $b->rawBlob($stickerset);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.StickerSet');
    }

    /**
     * messages.getStickers#d5a5d3a1 = messages.Stickers.
     */
    public function getStickers(string $emoticon, int $hash): mixed
    {
        $b = Builder::ctor(0xD5A5D3A1);
        $b->string((string)$emoticon);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Stickers');
    }

    /**
     * messages.getSuggestedDialogFilters#a29cd42c = Vector<DialogFilterSuggested>.
     */
    public function getSuggestedDialogFilters(): mixed
    {
        $b = Builder::ctor(0xA29CD42C);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<DialogFilterSuggested>');
    }

    /**
     * messages.getTopReactions#bb8125ba = messages.Reactions.
     */
    public function getTopReactions(int $limit, int $hash): mixed
    {
        $b = Builder::ctor(0xBB8125BA);
        $b->int((int)$limit);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Reactions');
    }

    /**
     * messages.getUnreadMentions#f107e790 = messages.Messages.
     */
    public function getUnreadMentions(mixed $peer, int $offset_id, int $add_offset, int $limit, int $max_id, int $min_id, ?int $top_msg_id = null): mixed
    {
        $flags = 0;
        if ($top_msg_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xF107E790);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($top_msg_id !== null) { $b->int((int)$top_msg_id); }
        $b->int((int)$offset_id);
        $b->int((int)$add_offset);
        $b->int((int)$limit);
        $b->int((int)$max_id);
        $b->int((int)$min_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getUnreadPollVotes#43286cf2 = messages.Messages.
     */
    public function getUnreadPollVotes(mixed $peer, int $offset_id, int $add_offset, int $limit, int $max_id, int $min_id, ?int $top_msg_id = null): mixed
    {
        $flags = 0;
        if ($top_msg_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x43286CF2);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($top_msg_id !== null) { $b->int((int)$top_msg_id); }
        $b->int((int)$offset_id);
        $b->int((int)$add_offset);
        $b->int((int)$limit);
        $b->int((int)$max_id);
        $b->int((int)$min_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getUnreadReactions#bd7f90ac = messages.Messages.
     */
    public function getUnreadReactions(mixed $peer, int $offset_id, int $add_offset, int $limit, int $max_id, int $min_id, ?int $top_msg_id = null, mixed $saved_peer_id = null): mixed
    {
        $flags = 0;
        if ($top_msg_id !== null) { $flags |= (1 << 0); }
        if ($saved_peer_id !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xBD7F90AC);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($top_msg_id !== null) { $b->int((int)$top_msg_id); }
        if ($saved_peer_id !== null) { $b->rawBlob($this->peerBlob($saved_peer_id)); }
        $b->int((int)$offset_id);
        $b->int((int)$add_offset);
        $b->int((int)$limit);
        $b->int((int)$max_id);
        $b->int((int)$min_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.getWebPage#8d9692a3 = messages.WebPage.
     */
    public function getWebPage(string $url, int $hash): mixed
    {
        $b = Builder::ctor(0x8D9692A3);
        $b->string((string)$url);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.WebPage');
    }

    /**
     * messages.getWebPagePreview#570d6f6f = messages.WebPagePreview.
     */
    public function getWebPagePreview(string $message, ?array $entities = null): mixed
    {
        $flags = 0;
        if ($entities !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x570D6F6F);
        $b->int($flags);
        $b->string((string)$message);
        if ($entities !== null) { $b->vector($entities); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.WebPagePreview');
    }

    /**
     * messages.hideAllChatJoinRequests#e085f4ea = Updates.
     */
    public function hideAllChatJoinRequests(mixed $peer, bool $approved = false, ?string $link = null): mixed
    {
        $flags = 0;
        if ($approved) { $flags |= (1 << 0); }
        if ($link !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xE085F4EA);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($link !== null) { $b->string((string)$link); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.hideChatJoinRequest#7fe7e815 = Updates.
     */
    public function hideChatJoinRequest(mixed $peer, mixed $user_id, bool $approved = false): mixed
    {
        $flags = 0;
        if ($approved) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x7FE7E815);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peers()->resolveUser($user_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.hidePeerSettingsBar#4facb138 = Bool.
     */
    public function hidePeerSettingsBar(mixed $peer): mixed
    {
        $b = Builder::ctor(0x4FACB138);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.importChatInvite#de91436e = messages.ChatInviteJoinResult.
     */
    public function importChatInvite(string $hash): mixed
    {
        $b = Builder::ctor(0xDE91436E);
        $b->string((string)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ChatInviteJoinResult');
    }

    /**
     * messages.initHistoryImport#34090c3b = messages.HistoryImport.
     */
    public function initHistoryImport(mixed $peer, string $file, int $media_count): mixed
    {
        $b = Builder::ctor(0x34090C3B);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($file);
        $b->int((int)$media_count);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.HistoryImport');
    }

    /**
     * messages.installStickerSet#c78fe460 = messages.StickerSetInstallResult.
     */
    public function installStickerSet(string $stickerset, bool $archived): mixed
    {
        $b = Builder::ctor(0xC78FE460);
        $b->rawBlob($stickerset);
        $b->bool((bool)$archived);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.StickerSetInstallResult');
    }

    /**
     * messages.markDialogUnread#8c5006f8 = Bool.
     */
    public function markDialogUnread(mixed $peer, bool $unread = false, mixed $parent_peer = null): mixed
    {
        $flags = 0;
        if ($unread) { $flags |= (1 << 0); }
        if ($parent_peer !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x8C5006F8);
        $b->int($flags);
        if ($parent_peer !== null) { $b->rawBlob($this->peerBlob($parent_peer)); }
        $b->rawBlob($this->peers()->resolveDialogPeer($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.migrateChat#a2875319 = Updates.
     */
    public function migrateChat(int $chat_id): mixed
    {
        $b = Builder::ctor(0xA2875319);
        $b->long((int)$chat_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.prolongWebView#b0d81a83 = Bool.
     */
    public function prolongWebView(mixed $peer, mixed $bot, int $query_id, bool $silent = false, ?string $reply_to = null, mixed $send_as = null): mixed
    {
        $flags = 0;
        if ($silent) { $flags |= (1 << 5); }
        if ($reply_to !== null) { $flags |= (1 << 0); }
        if ($send_as !== null) { $flags |= (1 << 13); }
        $b = Builder::ctor(0xB0D81A83);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->long((int)$query_id);
        if ($reply_to !== null) { $b->rawBlob($reply_to); }
        if ($send_as !== null) { $b->rawBlob($this->peerBlob($send_as)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.rateTranscribedAudio#7f1d072f = Bool.
     */
    public function rateTranscribedAudio(mixed $peer, int $msg_id, int $transcription_id, bool $good): mixed
    {
        $b = Builder::ctor(0x7F1D072F);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->long((int)$transcription_id);
        $b->bool((bool)$good);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.readDiscussion#f731a9f4 = Bool.
     */
    public function readDiscussion(mixed $peer, int $msg_id, int $read_max_id): mixed
    {
        $b = Builder::ctor(0xF731A9F4);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->int((int)$read_max_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.readEncryptedHistory#7f4b690a = Bool.
     */
    public function readEncryptedHistory(string $peer, int $max_date): mixed
    {
        $b = Builder::ctor(0x7F4B690A);
        $b->rawBlob($peer);
        $b->int((int)$max_date);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.readFeaturedStickers#5b118126 = Bool.
     */
    public function readFeaturedStickers(array $id): mixed
    {
        $b = Builder::ctor(0x5B118126);
        $b->vectorLong($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.readHistory#e306d3a = messages.AffectedMessages.
     */
    public function readHistory(mixed $peer, int $max_id): mixed
    {
        $b = Builder::ctor(0xE306D3A);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$max_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedMessages');
    }

    /**
     * messages.readMentions#36e5bf4d = messages.AffectedHistory.
     */
    public function readMentions(mixed $peer, ?int $top_msg_id = null): mixed
    {
        $flags = 0;
        if ($top_msg_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x36E5BF4D);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($top_msg_id !== null) { $b->int((int)$top_msg_id); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedHistory');
    }

    /**
     * messages.readMessageContents#36a73f77 = messages.AffectedMessages.
     */
    public function readMessageContents(array $id): mixed
    {
        $b = Builder::ctor(0x36A73F77);
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedMessages');
    }

    /**
     * messages.readPollVotes#1720b4d8 = messages.AffectedHistory.
     */
    public function readPollVotes(mixed $peer, ?int $top_msg_id = null): mixed
    {
        $flags = 0;
        if ($top_msg_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x1720B4D8);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($top_msg_id !== null) { $b->int((int)$top_msg_id); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedHistory');
    }

    /**
     * messages.readReactions#9ec44f93 = messages.AffectedHistory.
     */
    public function readReactions(mixed $peer, ?int $top_msg_id = null, mixed $saved_peer_id = null): mixed
    {
        $flags = 0;
        if ($top_msg_id !== null) { $flags |= (1 << 0); }
        if ($saved_peer_id !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x9EC44F93);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($top_msg_id !== null) { $b->int((int)$top_msg_id); }
        if ($saved_peer_id !== null) { $b->rawBlob($this->peerBlob($saved_peer_id)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedHistory');
    }

    /**
     * messages.readSavedHistory#ba4a3b5b = Bool.
     */
    public function readSavedHistory(mixed $parent_peer, mixed $peer, int $max_id): mixed
    {
        $b = Builder::ctor(0xBA4A3B5B);
        $b->rawBlob($this->peerBlob($parent_peer));
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$max_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.receivedMessages#5a954c0 = Vector<ReceivedNotifyMessage>.
     */
    public function receivedMessages(int $max_id): mixed
    {
        $b = Builder::ctor(0x5A954C0);
        $b->int((int)$max_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<ReceivedNotifyMessage>');
    }

    /**
     * messages.receivedQueue#55a5bb66 = Vector<long>.
     */
    public function receivedQueue(int $max_qts): mixed
    {
        $b = Builder::ctor(0x55A5BB66);
        $b->int((int)$max_qts);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<long>');
    }

    /**
     * messages.reorderPinnedDialogs#3b1adf37 = Bool.
     */
    public function reorderPinnedDialogs(int $folder_id, array $order, bool $force = false): mixed
    {
        $flags = 0;
        if ($force) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x3B1ADF37);
        $b->int($flags);
        $b->int((int)$folder_id);
        $b->vector(array_map(fn($x) => $this->peers()->resolveDialogPeer($x), $order));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.reorderPinnedForumTopics#e7841f0 = Updates.
     */
    public function reorderPinnedForumTopics(mixed $peer, array $order, bool $force = false): mixed
    {
        $flags = 0;
        if ($force) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xE7841F0);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($order);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.reorderPinnedSavedDialogs#8b716587 = Bool.
     */
    public function reorderPinnedSavedDialogs(array $order, bool $force = false): mixed
    {
        $flags = 0;
        if ($force) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x8B716587);
        $b->int($flags);
        $b->vector(array_map(fn($x) => $this->peers()->resolveDialogPeer($x), $order));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.reorderQuickReplies#60331907 = Bool.
     */
    public function reorderQuickReplies(array $order): mixed
    {
        $b = Builder::ctor(0x60331907);
        $b->vectorInt($order);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.reorderStickerSets#78337739 = Bool.
     */
    public function reorderStickerSets(array $order, bool $masks = false, bool $emojis = false): mixed
    {
        $flags = 0;
        if ($masks) { $flags |= (1 << 0); }
        if ($emojis) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x78337739);
        $b->int($flags);
        $b->vectorLong($order);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.report#fc78af9b = ReportResult.
     */
    public function report(mixed $peer, array $id, string $option, string $message): mixed
    {
        $b = Builder::ctor(0xFC78AF9B);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        $b->string((string)$option);
        $b->string((string)$message);
        return Deserializer::parse($this->client->rpc($b->build()), 'ReportResult');
    }

    /**
     * messages.reportEncryptedSpam#4b0c8c0f = Bool.
     */
    public function reportEncryptedSpam(string $peer): mixed
    {
        $b = Builder::ctor(0x4B0C8C0F);
        $b->rawBlob($peer);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.reportMessagesDelivery#5a6d7395 = Bool.
     */
    public function reportMessagesDelivery(mixed $peer, array $id, bool $push = false): mixed
    {
        $flags = 0;
        if ($push) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x5A6D7395);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.reportMusicListen#ddbcd819 = Bool.
     */
    public function reportMusicListen(string $id, int $listened_duration): mixed
    {
        $b = Builder::ctor(0xDDBCD819);
        $b->rawBlob($id);
        $b->int((int)$listened_duration);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.reportReaction#3f64c076 = Bool.
     */
    public function reportReaction(mixed $peer, int $id, mixed $reaction_peer): mixed
    {
        $b = Builder::ctor(0x3F64C076);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        $b->rawBlob($this->peerBlob($reaction_peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.reportReadMetrics#4067c5e6 = Bool.
     */
    public function reportReadMetrics(mixed $peer, array $metrics): mixed
    {
        $b = Builder::ctor(0x4067C5E6);
        $b->rawBlob($this->peerBlob($peer));
        $b->vector($metrics);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.reportSpam#cf1592db = Bool.
     */
    public function reportSpam(mixed $peer): mixed
    {
        $b = Builder::ctor(0xCF1592DB);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.reportSponsoredMessage#12cbf0c4 = channels.SponsoredMessageReportResult.
     */
    public function reportSponsoredMessage(string $random_id, string $option): mixed
    {
        $b = Builder::ctor(0x12CBF0C4);
        $b->string((string)$random_id);
        $b->string((string)$option);
        return Deserializer::parse($this->client->rpc($b->build()), 'channels.SponsoredMessageReportResult');
    }

    /**
     * messages.requestAppWebView#53618bce = WebViewResult.
     */
    public function requestAppWebView(mixed $peer, string $app, string $platform, bool $write_allowed = false, bool $compact = false, bool $fullscreen = false, ?string $start_param = null, ?string $theme_params = null): mixed
    {
        $flags = 0;
        if ($write_allowed) { $flags |= (1 << 0); }
        if ($compact) { $flags |= (1 << 7); }
        if ($fullscreen) { $flags |= (1 << 8); }
        if ($start_param !== null) { $flags |= (1 << 1); }
        if ($theme_params !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x53618BCE);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($app);
        if ($start_param !== null) { $b->string((string)$start_param); }
        if ($theme_params !== null) { $b->rawBlob($theme_params); }
        $b->string((string)$platform);
        return Deserializer::parse($this->client->rpc($b->build()), 'WebViewResult');
    }

    /**
     * messages.requestChatJoinWebView#ba9ee679 = WebViewResult.
     */
    public function requestChatJoinWebView(int $query_id, string $platform, ?string $theme_params = null): mixed
    {
        $flags = 0;
        if ($theme_params !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xBA9EE679);
        $b->int($flags);
        $b->long((int)$query_id);
        if ($theme_params !== null) { $b->rawBlob($theme_params); }
        $b->string((string)$platform);
        return Deserializer::parse($this->client->rpc($b->build()), 'WebViewResult');
    }

    /**
     * messages.requestEncryption#f64daf43 = EncryptedChat.
     */
    public function requestEncryption(mixed $user_id, int $random_id, string $g_a): mixed
    {
        $b = Builder::ctor(0xF64DAF43);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->int((int)$random_id);
        $b->string((string)$g_a);
        return Deserializer::parse($this->client->rpc($b->build()), 'EncryptedChat');
    }

    /**
     * messages.requestMainWebView#c9e01e7b = WebViewResult.
     */
    public function requestMainWebView(mixed $peer, mixed $bot, string $platform, bool $compact = false, bool $fullscreen = false, ?string $start_param = null, ?string $theme_params = null): mixed
    {
        $flags = 0;
        if ($compact) { $flags |= (1 << 7); }
        if ($fullscreen) { $flags |= (1 << 8); }
        if ($start_param !== null) { $flags |= (1 << 1); }
        if ($theme_params !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xC9E01E7B);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peers()->resolveUser($bot));
        if ($start_param !== null) { $b->string((string)$start_param); }
        if ($theme_params !== null) { $b->rawBlob($theme_params); }
        $b->string((string)$platform);
        return Deserializer::parse($this->client->rpc($b->build()), 'WebViewResult');
    }

    /**
     * messages.requestSimpleWebView#413a3e73 = WebViewResult.
     */
    public function requestSimpleWebView(mixed $bot, string $platform, bool $from_switch_webview = false, bool $from_side_menu = false, bool $compact = false, bool $fullscreen = false, ?string $url = null, ?string $start_param = null, ?string $theme_params = null): mixed
    {
        $flags = 0;
        if ($from_switch_webview) { $flags |= (1 << 1); }
        if ($from_side_menu) { $flags |= (1 << 2); }
        if ($compact) { $flags |= (1 << 7); }
        if ($fullscreen) { $flags |= (1 << 8); }
        if ($url !== null) { $flags |= (1 << 3); }
        if ($start_param !== null) { $flags |= (1 << 4); }
        if ($theme_params !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x413A3E73);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveUser($bot));
        if ($url !== null) { $b->string((string)$url); }
        if ($start_param !== null) { $b->string((string)$start_param); }
        if ($theme_params !== null) { $b->rawBlob($theme_params); }
        $b->string((string)$platform);
        return Deserializer::parse($this->client->rpc($b->build()), 'WebViewResult');
    }

    /**
     * messages.requestUrlAuth#894cc99c = UrlAuthResult.
     */
    public function requestUrlAuth(mixed $peer = null, ?int $msg_id = null, ?int $button_id = null, ?string $url = null, ?string $in_app_origin = null): mixed
    {
        $flags = 0;
        if ($peer !== null) { $flags |= (1 << 1); }
        if ($msg_id !== null) { $flags |= (1 << 1); }
        if ($button_id !== null) { $flags |= (1 << 1); }
        if ($url !== null) { $flags |= (1 << 2); }
        if ($in_app_origin !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x894CC99C);
        $b->int($flags);
        if ($peer !== null) { $b->rawBlob($this->peerBlob($peer)); }
        if ($msg_id !== null) { $b->int((int)$msg_id); }
        if ($button_id !== null) { $b->int((int)$button_id); }
        if ($url !== null) { $b->string((string)$url); }
        if ($in_app_origin !== null) { $b->string((string)$in_app_origin); }
        return Deserializer::parse($this->client->rpc($b->build()), 'UrlAuthResult');
    }

    /**
     * messages.requestWebView#269dc2c1 = WebViewResult.
     */
    public function requestWebView(mixed $peer, mixed $bot, string $platform, bool $from_bot_menu = false, bool $silent = false, bool $compact = false, bool $fullscreen = false, ?string $url = null, ?string $start_param = null, ?string $theme_params = null, ?string $reply_to = null, mixed $send_as = null): mixed
    {
        $flags = 0;
        if ($from_bot_menu) { $flags |= (1 << 4); }
        if ($silent) { $flags |= (1 << 5); }
        if ($compact) { $flags |= (1 << 7); }
        if ($fullscreen) { $flags |= (1 << 8); }
        if ($url !== null) { $flags |= (1 << 1); }
        if ($start_param !== null) { $flags |= (1 << 3); }
        if ($theme_params !== null) { $flags |= (1 << 2); }
        if ($reply_to !== null) { $flags |= (1 << 0); }
        if ($send_as !== null) { $flags |= (1 << 13); }
        $b = Builder::ctor(0x269DC2C1);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peers()->resolveUser($bot));
        if ($url !== null) { $b->string((string)$url); }
        if ($start_param !== null) { $b->string((string)$start_param); }
        if ($theme_params !== null) { $b->rawBlob($theme_params); }
        $b->string((string)$platform);
        if ($reply_to !== null) { $b->rawBlob($reply_to); }
        if ($send_as !== null) { $b->rawBlob($this->peerBlob($send_as)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'WebViewResult');
    }

    /**
     * messages.saveDefaultSendAs#ccfddf96 = Bool.
     */
    public function saveDefaultSendAs(mixed $peer, mixed $send_as): mixed
    {
        $b = Builder::ctor(0xCCFDDF96);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peerBlob($send_as));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.saveDraft#ad0fa15c = Bool.
     */
    public function saveDraft(mixed $peer, string $message, bool $no_webpage = false, bool $invert_media = false, ?string $reply_to = null, ?array $entities = null, ?string $media = null, ?int $effect = null, ?string $suggested_post = null, ?string $rich_message = null): mixed
    {
        $flags = 0;
        if ($no_webpage) { $flags |= (1 << 1); }
        if ($invert_media) { $flags |= (1 << 6); }
        if ($reply_to !== null) { $flags |= (1 << 4); }
        if ($entities !== null) { $flags |= (1 << 3); }
        if ($media !== null) { $flags |= (1 << 5); }
        if ($effect !== null) { $flags |= (1 << 7); }
        if ($suggested_post !== null) { $flags |= (1 << 8); }
        if ($rich_message !== null) { $flags |= (1 << 9); }
        $b = Builder::ctor(0xAD0FA15C);
        $b->int($flags);
        if ($reply_to !== null) { $b->rawBlob($reply_to); }
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$message);
        if ($entities !== null) { $b->vector($entities); }
        if ($media !== null) { $b->rawBlob($media); }
        if ($effect !== null) { $b->long((int)$effect); }
        if ($suggested_post !== null) { $b->rawBlob($suggested_post); }
        if ($rich_message !== null) { $b->rawBlob($rich_message); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.saveGif#327a30cb = Bool.
     */
    public function saveGif(string $id, bool $unsave): mixed
    {
        $b = Builder::ctor(0x327A30CB);
        $b->rawBlob($id);
        $b->bool((bool)$unsave);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.savePreparedInlineMessage#f21f7f2f = messages.BotPreparedInlineMessage.
     */
    public function savePreparedInlineMessage(string $result, mixed $user_id, ?array $peer_types = null): mixed
    {
        $flags = 0;
        if ($peer_types !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xF21F7F2F);
        $b->int($flags);
        $b->rawBlob($result);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        if ($peer_types !== null) { $b->vector($peer_types); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.BotPreparedInlineMessage');
    }

    /**
     * messages.saveRecentSticker#392718f8 = Bool.
     */
    public function saveRecentSticker(string $id, bool $unsave, bool $attached = false): mixed
    {
        $flags = 0;
        if ($attached) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x392718F8);
        $b->int($flags);
        $b->rawBlob($id);
        $b->bool((bool)$unsave);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.search#29ee847a = messages.Messages.
     */
    public function search(mixed $peer, string $q, string $filter, int $min_date, int $max_date, int $offset_id, int $add_offset, int $limit, int $max_id, int $min_id, int $hash, mixed $from_id = null, mixed $saved_peer_id = null, ?array $saved_reaction = null, ?int $top_msg_id = null): mixed
    {
        $flags = 0;
        if ($from_id !== null) { $flags |= (1 << 0); }
        if ($saved_peer_id !== null) { $flags |= (1 << 2); }
        if ($saved_reaction !== null) { $flags |= (1 << 3); }
        if ($top_msg_id !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x29EE847A);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$q);
        if ($from_id !== null) { $b->rawBlob($this->peerBlob($from_id)); }
        if ($saved_peer_id !== null) { $b->rawBlob($this->peerBlob($saved_peer_id)); }
        if ($saved_reaction !== null) { $b->vector($saved_reaction); }
        if ($top_msg_id !== null) { $b->int((int)$top_msg_id); }
        $b->rawBlob($filter);
        $b->int((int)$min_date);
        $b->int((int)$max_date);
        $b->int((int)$offset_id);
        $b->int((int)$add_offset);
        $b->int((int)$limit);
        $b->int((int)$max_id);
        $b->int((int)$min_id);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.searchCustomEmoji#2c11c0d7 = EmojiList.
     */
    public function searchCustomEmoji(string $emoticon, int $hash): mixed
    {
        $b = Builder::ctor(0x2C11C0D7);
        $b->string((string)$emoticon);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'EmojiList');
    }

    /**
     * messages.searchEmojiStickerSets#92b4494c = messages.FoundStickerSets.
     */
    public function searchEmojiStickerSets(string $q, int $hash, bool $exclude_featured = false): mixed
    {
        $flags = 0;
        if ($exclude_featured) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x92B4494C);
        $b->int($flags);
        $b->string((string)$q);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.FoundStickerSets');
    }

    /**
     * messages.searchGlobal#6126a43c = messages.Messages.
     */
    public function searchGlobal(string $q, string $filter, int $min_date, int $max_date, int $offset_rate, mixed $offset_peer, int $offset_id, int $limit, bool $broadcasts_only = false, bool $groups_only = false, bool $users_only = false, ?int $folder_id = null, mixed $community = null): mixed
    {
        $flags = 0;
        if ($broadcasts_only) { $flags |= (1 << 1); }
        if ($groups_only) { $flags |= (1 << 2); }
        if ($users_only) { $flags |= (1 << 3); }
        if ($folder_id !== null) { $flags |= (1 << 0); }
        if ($community !== null) { $flags |= (1 << 4); }
        $b = Builder::ctor(0x6126A43C);
        $b->int($flags);
        if ($folder_id !== null) { $b->int((int)$folder_id); }
        if ($community !== null) { $b->rawBlob($this->channelBlob($community)); }
        $b->string((string)$q);
        $b->rawBlob($filter);
        $b->int((int)$min_date);
        $b->int((int)$max_date);
        $b->int((int)$offset_rate);
        $b->rawBlob($this->peerBlob($offset_peer));
        $b->int((int)$offset_id);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.searchSentMedia#107e31a0 = messages.Messages.
     */
    public function searchSentMedia(string $q, string $filter, int $limit): mixed
    {
        $b = Builder::ctor(0x107E31A0);
        $b->string((string)$q);
        $b->rawBlob($filter);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * messages.searchStickerSets#35705b8a = messages.FoundStickerSets.
     */
    public function searchStickerSets(string $q, int $hash, bool $exclude_featured = false): mixed
    {
        $flags = 0;
        if ($exclude_featured) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x35705B8A);
        $b->int($flags);
        $b->string((string)$q);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.FoundStickerSets');
    }

    /**
     * messages.searchStickers#29b1c66a = messages.FoundStickers.
     */
    public function searchStickers(string $q, string $emoticon, array $lang_code, int $offset, int $limit, int $hash, bool $emojis = false): mixed
    {
        $flags = 0;
        if ($emojis) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x29B1C66A);
        $b->int($flags);
        $b->string((string)$q);
        $b->string((string)$emoticon);
        $b->vectorString($lang_code);
        $b->int((int)$offset);
        $b->int((int)$limit);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.FoundStickers');
    }

    /**
     * messages.sendBotRequestedPeer#6c5cf2a7 = Updates.
     */
    public function sendBotRequestedPeer(mixed $peer, int $button_id, array $requested_peers, ?int $msg_id = null, ?string $webapp_req_id = null): mixed
    {
        $flags = 0;
        if ($msg_id !== null) { $flags |= (1 << 0); }
        if ($webapp_req_id !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x6C5CF2A7);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($msg_id !== null) { $b->int((int)$msg_id); }
        if ($webapp_req_id !== null) { $b->string((string)$webapp_req_id); }
        $b->int((int)$button_id);
        $b->vector(array_map(fn($x) => $this->peerBlob($x), $requested_peers));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendEncrypted#44fa7a15 = messages.SentEncryptedMessage.
     */
    public function sendEncrypted(string $peer, int $random_id, string $data, bool $silent = false): mixed
    {
        $flags = 0;
        if ($silent) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x44FA7A15);
        $b->int($flags);
        $b->rawBlob($peer);
        $b->long((int)$random_id);
        $b->string((string)$data);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.SentEncryptedMessage');
    }

    /**
     * messages.sendEncryptedFile#5559481d = messages.SentEncryptedMessage.
     */
    public function sendEncryptedFile(string $peer, int $random_id, string $data, string $file, bool $silent = false): mixed
    {
        $flags = 0;
        if ($silent) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x5559481D);
        $b->int($flags);
        $b->rawBlob($peer);
        $b->long((int)$random_id);
        $b->string((string)$data);
        $b->rawBlob($file);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.SentEncryptedMessage');
    }

    /**
     * messages.sendEncryptedService#32d439a4 = messages.SentEncryptedMessage.
     */
    public function sendEncryptedService(string $peer, int $random_id, string $data): mixed
    {
        $b = Builder::ctor(0x32D439A4);
        $b->rawBlob($peer);
        $b->long((int)$random_id);
        $b->string((string)$data);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.SentEncryptedMessage');
    }

    /**
     * messages.sendInlineBotResult#c0cf7646 = Updates.
     */
    public function sendInlineBotResult(mixed $peer, int $random_id, int $query_id, string $id, bool $silent = false, bool $background = false, bool $clear_draft = false, bool $hide_via = false, ?string $reply_to = null, ?int $schedule_date = null, mixed $send_as = null, ?string $quick_reply_shortcut = null, ?int $allow_paid_stars = null): mixed
    {
        $flags = 0;
        if ($silent) { $flags |= (1 << 5); }
        if ($background) { $flags |= (1 << 6); }
        if ($clear_draft) { $flags |= (1 << 7); }
        if ($hide_via) { $flags |= (1 << 11); }
        if ($reply_to !== null) { $flags |= (1 << 0); }
        if ($schedule_date !== null) { $flags |= (1 << 10); }
        if ($send_as !== null) { $flags |= (1 << 13); }
        if ($quick_reply_shortcut !== null) { $flags |= (1 << 17); }
        if ($allow_paid_stars !== null) { $flags |= (1 << 21); }
        $b = Builder::ctor(0xC0CF7646);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($reply_to !== null) { $b->rawBlob($reply_to); }
        $b->long((int)$random_id);
        $b->long((int)$query_id);
        $b->string((string)$id);
        if ($schedule_date !== null) { $b->int((int)$schedule_date); }
        if ($send_as !== null) { $b->rawBlob($this->peerBlob($send_as)); }
        if ($quick_reply_shortcut !== null) { $b->rawBlob($quick_reply_shortcut); }
        if ($allow_paid_stars !== null) { $b->long((int)$allow_paid_stars); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendMedia#330e77f = Updates.
     */
    public function sendMedia(mixed $peer, string $media, string $message, int $random_id, bool $silent = false, bool $background = false, bool $clear_draft = false, bool $noforwards = false, bool $update_stickersets_order = false, bool $invert_media = false, bool $allow_paid_floodskip = false, ?string $reply_to = null, ?string $reply_markup = null, ?array $entities = null, ?int $schedule_date = null, ?int $schedule_repeat_period = null, mixed $send_as = null, ?string $quick_reply_shortcut = null, ?int $effect = null, ?int $allow_paid_stars = null, ?string $suggested_post = null): mixed
    {
        $flags = 0;
        if ($silent) { $flags |= (1 << 5); }
        if ($background) { $flags |= (1 << 6); }
        if ($clear_draft) { $flags |= (1 << 7); }
        if ($noforwards) { $flags |= (1 << 14); }
        if ($update_stickersets_order) { $flags |= (1 << 15); }
        if ($invert_media) { $flags |= (1 << 16); }
        if ($allow_paid_floodskip) { $flags |= (1 << 19); }
        if ($reply_to !== null) { $flags |= (1 << 0); }
        if ($reply_markup !== null) { $flags |= (1 << 2); }
        if ($entities !== null) { $flags |= (1 << 3); }
        if ($schedule_date !== null) { $flags |= (1 << 10); }
        if ($schedule_repeat_period !== null) { $flags |= (1 << 24); }
        if ($send_as !== null) { $flags |= (1 << 13); }
        if ($quick_reply_shortcut !== null) { $flags |= (1 << 17); }
        if ($effect !== null) { $flags |= (1 << 18); }
        if ($allow_paid_stars !== null) { $flags |= (1 << 21); }
        if ($suggested_post !== null) { $flags |= (1 << 22); }
        $b = Builder::ctor(0x330E77F);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($reply_to !== null) { $b->rawBlob($reply_to); }
        $b->rawBlob($media);
        $b->string((string)$message);
        $b->long((int)$random_id);
        if ($reply_markup !== null) { $b->rawBlob($reply_markup); }
        if ($entities !== null) { $b->vector($entities); }
        if ($schedule_date !== null) { $b->int((int)$schedule_date); }
        if ($schedule_repeat_period !== null) { $b->int((int)$schedule_repeat_period); }
        if ($send_as !== null) { $b->rawBlob($this->peerBlob($send_as)); }
        if ($quick_reply_shortcut !== null) { $b->rawBlob($quick_reply_shortcut); }
        if ($effect !== null) { $b->long((int)$effect); }
        if ($allow_paid_stars !== null) { $b->long((int)$allow_paid_stars); }
        if ($suggested_post !== null) { $b->rawBlob($suggested_post); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendMessage#fef48f62 = Updates.
     */
    public function sendMessage(mixed $peer, string $message, int $random_id, bool $no_webpage = false, bool $silent = false, bool $background = false, bool $clear_draft = false, bool $noforwards = false, bool $update_stickersets_order = false, bool $invert_media = false, bool $allow_paid_floodskip = false, ?string $reply_to = null, ?string $reply_markup = null, ?array $entities = null, ?int $schedule_date = null, ?int $schedule_repeat_period = null, mixed $send_as = null, ?string $quick_reply_shortcut = null, ?int $effect = null, ?int $allow_paid_stars = null, ?string $suggested_post = null, ?string $rich_message = null): mixed
    {
        $flags = 0;
        if ($no_webpage) { $flags |= (1 << 1); }
        if ($silent) { $flags |= (1 << 5); }
        if ($background) { $flags |= (1 << 6); }
        if ($clear_draft) { $flags |= (1 << 7); }
        if ($noforwards) { $flags |= (1 << 14); }
        if ($update_stickersets_order) { $flags |= (1 << 15); }
        if ($invert_media) { $flags |= (1 << 16); }
        if ($allow_paid_floodskip) { $flags |= (1 << 19); }
        if ($reply_to !== null) { $flags |= (1 << 0); }
        if ($reply_markup !== null) { $flags |= (1 << 2); }
        if ($entities !== null) { $flags |= (1 << 3); }
        if ($schedule_date !== null) { $flags |= (1 << 10); }
        if ($schedule_repeat_period !== null) { $flags |= (1 << 24); }
        if ($send_as !== null) { $flags |= (1 << 13); }
        if ($quick_reply_shortcut !== null) { $flags |= (1 << 17); }
        if ($effect !== null) { $flags |= (1 << 18); }
        if ($allow_paid_stars !== null) { $flags |= (1 << 21); }
        if ($suggested_post !== null) { $flags |= (1 << 22); }
        if ($rich_message !== null) { $flags |= (1 << 23); }
        $b = Builder::ctor(0xFEF48F62);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($reply_to !== null) { $b->rawBlob($reply_to); }
        $b->string((string)$message);
        $b->long((int)$random_id);
        if ($reply_markup !== null) { $b->rawBlob($reply_markup); }
        if ($entities !== null) { $b->vector($entities); }
        if ($schedule_date !== null) { $b->int((int)$schedule_date); }
        if ($schedule_repeat_period !== null) { $b->int((int)$schedule_repeat_period); }
        if ($send_as !== null) { $b->rawBlob($this->peerBlob($send_as)); }
        if ($quick_reply_shortcut !== null) { $b->rawBlob($quick_reply_shortcut); }
        if ($effect !== null) { $b->long((int)$effect); }
        if ($allow_paid_stars !== null) { $b->long((int)$allow_paid_stars); }
        if ($suggested_post !== null) { $b->rawBlob($suggested_post); }
        if ($rich_message !== null) { $b->rawBlob($rich_message); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendMultiMedia#1bf89d74 = Updates.
     */
    public function sendMultiMedia(mixed $peer, array $multi_media, bool $silent = false, bool $background = false, bool $clear_draft = false, bool $noforwards = false, bool $update_stickersets_order = false, bool $invert_media = false, bool $allow_paid_floodskip = false, ?string $reply_to = null, ?int $schedule_date = null, mixed $send_as = null, ?string $quick_reply_shortcut = null, ?int $effect = null, ?int $allow_paid_stars = null): mixed
    {
        $flags = 0;
        if ($silent) { $flags |= (1 << 5); }
        if ($background) { $flags |= (1 << 6); }
        if ($clear_draft) { $flags |= (1 << 7); }
        if ($noforwards) { $flags |= (1 << 14); }
        if ($update_stickersets_order) { $flags |= (1 << 15); }
        if ($invert_media) { $flags |= (1 << 16); }
        if ($allow_paid_floodskip) { $flags |= (1 << 19); }
        if ($reply_to !== null) { $flags |= (1 << 0); }
        if ($schedule_date !== null) { $flags |= (1 << 10); }
        if ($send_as !== null) { $flags |= (1 << 13); }
        if ($quick_reply_shortcut !== null) { $flags |= (1 << 17); }
        if ($effect !== null) { $flags |= (1 << 18); }
        if ($allow_paid_stars !== null) { $flags |= (1 << 21); }
        $b = Builder::ctor(0x1BF89D74);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($reply_to !== null) { $b->rawBlob($reply_to); }
        $b->vector($multi_media);
        if ($schedule_date !== null) { $b->int((int)$schedule_date); }
        if ($send_as !== null) { $b->rawBlob($this->peerBlob($send_as)); }
        if ($quick_reply_shortcut !== null) { $b->rawBlob($quick_reply_shortcut); }
        if ($effect !== null) { $b->long((int)$effect); }
        if ($allow_paid_stars !== null) { $b->long((int)$allow_paid_stars); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendPaidReaction#58bbcb50 = Updates.
     */
    public function sendPaidReaction(mixed $peer, int $msg_id, int $count, int $random_id, ?string $private_ = null): mixed
    {
        $flags = 0;
        if ($private_ !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x58BBCB50);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->int((int)$count);
        $b->long((int)$random_id);
        if ($private_ !== null) { $b->rawBlob($private_); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendQuickReplyMessages#6c750de1 = Updates.
     */
    public function sendQuickReplyMessages(mixed $peer, int $shortcut_id, array $id, array $random_id): mixed
    {
        $b = Builder::ctor(0x6C750DE1);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$shortcut_id);
        $b->vectorInt($id);
        $b->vectorLong($random_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendReaction#d30d78d4 = Updates.
     */
    public function sendReaction(mixed $peer, int $msg_id, bool $big = false, bool $add_to_recent = false, ?array $reaction = null): mixed
    {
        $flags = 0;
        if ($big) { $flags |= (1 << 1); }
        if ($add_to_recent) { $flags |= (1 << 2); }
        if ($reaction !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xD30D78D4);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        if ($reaction !== null) { $b->vector($reaction); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendScheduledMessages#bd38850a = Updates.
     */
    public function sendScheduledMessages(mixed $peer, array $id): mixed
    {
        $b = Builder::ctor(0xBD38850A);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendScreenshotNotification#a1405817 = Updates.
     */
    public function sendScreenshotNotification(mixed $peer, string $reply_to, int $random_id): mixed
    {
        $b = Builder::ctor(0xA1405817);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($reply_to);
        $b->long((int)$random_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendVote#10ea6184 = Updates.
     */
    public function sendVote(mixed $peer, int $msg_id, array $options): mixed
    {
        $b = Builder::ctor(0x10EA6184);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->vectorBytes($options);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendWebViewData#dc0242c8 = Updates.
     */
    public function sendWebViewData(mixed $bot, int $random_id, string $button_text, string $data): mixed
    {
        $b = Builder::ctor(0xDC0242C8);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->long((int)$random_id);
        $b->string((string)$button_text);
        $b->string((string)$data);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.sendWebViewResultMessage#a4314f5 = WebViewMessageSent.
     */
    public function sendWebViewResultMessage(string $bot_query_id, string $result): mixed
    {
        $b = Builder::ctor(0xA4314F5);
        $b->string((string)$bot_query_id);
        $b->rawBlob($result);
        return Deserializer::parse($this->client->rpc($b->build()), 'WebViewMessageSent');
    }

    /**
     * messages.setBotCallbackAnswer#d58f130a = Bool.
     */
    public function setBotCallbackAnswer(int $query_id, int $cache_time, bool $alert = false, ?string $message = null, ?string $url = null): mixed
    {
        $flags = 0;
        if ($alert) { $flags |= (1 << 1); }
        if ($message !== null) { $flags |= (1 << 0); }
        if ($url !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xD58F130A);
        $b->int($flags);
        $b->long((int)$query_id);
        if ($message !== null) { $b->string((string)$message); }
        if ($url !== null) { $b->string((string)$url); }
        $b->int((int)$cache_time);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.setBotGuestChatResult#b8f106e3 = InputBotInlineMessageID.
     */
    public function setBotGuestChatResult(int $query_id, string $result): mixed
    {
        $b = Builder::ctor(0xB8F106E3);
        $b->long((int)$query_id);
        $b->rawBlob($result);
        return Deserializer::parse($this->client->rpc($b->build()), 'InputBotInlineMessageID');
    }

    /**
     * messages.setBotPrecheckoutResults#9c2dd95 = Bool.
     */
    public function setBotPrecheckoutResults(int $query_id, bool $success = false, ?string $error = null): mixed
    {
        $flags = 0;
        if ($success) { $flags |= (1 << 1); }
        if ($error !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x9C2DD95);
        $b->int($flags);
        $b->long((int)$query_id);
        if ($error !== null) { $b->string((string)$error); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.setBotShippingResults#e5f672fa = Bool.
     */
    public function setBotShippingResults(int $query_id, ?string $error = null, ?array $shipping_options = null): mixed
    {
        $flags = 0;
        if ($error !== null) { $flags |= (1 << 0); }
        if ($shipping_options !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xE5F672FA);
        $b->int($flags);
        $b->long((int)$query_id);
        if ($error !== null) { $b->string((string)$error); }
        if ($shipping_options !== null) { $b->vector($shipping_options); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.setChatAvailableReactions#864b2581 = Updates.
     */
    public function setChatAvailableReactions(mixed $peer, string $available_reactions, ?int $reactions_limit = null, ?bool $paid_enabled = null): mixed
    {
        $flags = 0;
        if ($reactions_limit !== null) { $flags |= (1 << 0); }
        if ($paid_enabled !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x864B2581);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($available_reactions);
        if ($reactions_limit !== null) { $b->int((int)$reactions_limit); }
        if ($paid_enabled !== null) { $b->bool((bool)$paid_enabled); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.setChatTheme#81202c9 = Updates.
     */
    public function setChatTheme(mixed $peer, string $theme): mixed
    {
        $b = Builder::ctor(0x81202C9);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($theme);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.setChatWallPaper#8ffacae1 = Updates.
     */
    public function setChatWallPaper(mixed $peer, bool $for_both = false, bool $revert = false, ?string $wallpaper = null, ?string $settings = null, ?int $id = null): mixed
    {
        $flags = 0;
        if ($for_both) { $flags |= (1 << 3); }
        if ($revert) { $flags |= (1 << 4); }
        if ($wallpaper !== null) { $flags |= (1 << 0); }
        if ($settings !== null) { $flags |= (1 << 2); }
        if ($id !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x8FFACAE1);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($wallpaper !== null) { $b->rawBlob($wallpaper); }
        if ($settings !== null) { $b->rawBlob($settings); }
        if ($id !== null) { $b->int((int)$id); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.setDefaultHistoryTTL#9eb51445 = Bool.
     */
    public function setDefaultHistoryTTL(int $period): mixed
    {
        $b = Builder::ctor(0x9EB51445);
        $b->int((int)$period);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.setDefaultReaction#4f47a016 = Bool.
     */
    public function setDefaultReaction(string $reaction): mixed
    {
        $b = Builder::ctor(0x4F47A016);
        $b->rawBlob($reaction);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.setEncryptedTyping#791451ed = Bool.
     */
    public function setEncryptedTyping(string $peer, bool $typing): mixed
    {
        $b = Builder::ctor(0x791451ED);
        $b->rawBlob($peer);
        $b->bool((bool)$typing);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.setGameScore#8ef8ecc0 = Updates.
     */
    public function setGameScore(mixed $peer, int $id, mixed $user_id, int $score, bool $edit_message = false, bool $force = false): mixed
    {
        $flags = 0;
        if ($edit_message) { $flags |= (1 << 0); }
        if ($force) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x8EF8ECC0);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->int((int)$score);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.setHistoryTTL#b80e5fe4 = Updates.
     */
    public function setHistoryTTL(mixed $peer, int $period): mixed
    {
        $b = Builder::ctor(0xB80E5FE4);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$period);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.setInlineBotResults#bb12a419 = Bool.
     */
    public function setInlineBotResults(int $query_id, array $results, int $cache_time, bool $gallery = false, bool $private_ = false, ?string $next_offset = null, ?string $switch_pm = null, ?string $switch_webview = null): mixed
    {
        $flags = 0;
        if ($gallery) { $flags |= (1 << 0); }
        if ($private_) { $flags |= (1 << 1); }
        if ($next_offset !== null) { $flags |= (1 << 2); }
        if ($switch_pm !== null) { $flags |= (1 << 3); }
        if ($switch_webview !== null) { $flags |= (1 << 4); }
        $b = Builder::ctor(0xBB12A419);
        $b->int($flags);
        $b->long((int)$query_id);
        $b->vector($results);
        $b->int((int)$cache_time);
        if ($next_offset !== null) { $b->string((string)$next_offset); }
        if ($switch_pm !== null) { $b->rawBlob($switch_pm); }
        if ($switch_webview !== null) { $b->rawBlob($switch_webview); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.setInlineGameScore#15ad9f64 = Bool.
     */
    public function setInlineGameScore(string $id, mixed $user_id, int $score, bool $edit_message = false, bool $force = false): mixed
    {
        $flags = 0;
        if ($edit_message) { $flags |= (1 << 0); }
        if ($force) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x15AD9F64);
        $b->int($flags);
        $b->rawBlob($id);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->int((int)$score);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.setTyping#58943ee2 = Bool.
     */
    public function setTyping(mixed $peer, string $action, ?int $top_msg_id = null): mixed
    {
        $flags = 0;
        if ($top_msg_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x58943EE2);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($top_msg_id !== null) { $b->int((int)$top_msg_id); }
        $b->rawBlob($action);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.startBot#e6df7378 = Updates.
     */
    public function startBot(mixed $bot, mixed $peer, int $random_id, string $start_param): mixed
    {
        $b = Builder::ctor(0xE6DF7378);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->rawBlob($this->peerBlob($peer));
        $b->long((int)$random_id);
        $b->string((string)$start_param);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.startHistoryImport#b43df344 = Bool.
     */
    public function startHistoryImport(mixed $peer, int $import_id): mixed
    {
        $b = Builder::ctor(0xB43DF344);
        $b->rawBlob($this->peerBlob($peer));
        $b->long((int)$import_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.summarizeText#abbbd346 = TextWithEntities.
     */
    public function summarizeText(mixed $peer, int $id, ?string $to_lang = null, ?string $tone = null): mixed
    {
        $flags = 0;
        if ($to_lang !== null) { $flags |= (1 << 0); }
        if ($tone !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xABBBD346);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        if ($to_lang !== null) { $b->string((string)$to_lang); }
        if ($tone !== null) { $b->string((string)$tone); }
        return Deserializer::parse($this->client->rpc($b->build()), 'TextWithEntities');
    }

    /**
     * messages.toggleBotInAttachMenu#69f59d69 = Bool.
     */
    public function toggleBotInAttachMenu(mixed $bot, bool $enabled, bool $write_allowed = false): mixed
    {
        $flags = 0;
        if ($write_allowed) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x69F59D69);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveUser($bot));
        $b->bool((bool)$enabled);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.toggleDialogFilterTags#fd2dda49 = Bool.
     */
    public function toggleDialogFilterTags(bool $enabled): mixed
    {
        $b = Builder::ctor(0xFD2DDA49);
        $b->bool((bool)$enabled);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.toggleDialogPin#a731e257 = Bool.
     */
    public function toggleDialogPin(mixed $peer, bool $pinned = false): mixed
    {
        $flags = 0;
        if ($pinned) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xA731E257);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveDialogPeer($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.toggleNoForwards#b2081a35 = Updates.
     */
    public function toggleNoForwards(mixed $peer, bool $enabled, ?int $request_msg_id = null): mixed
    {
        $flags = 0;
        if ($request_msg_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xB2081A35);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->bool((bool)$enabled);
        if ($request_msg_id !== null) { $b->int((int)$request_msg_id); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.togglePaidReactionPrivacy#435885b5 = Bool.
     */
    public function togglePaidReactionPrivacy(mixed $peer, int $msg_id, string $private_): mixed
    {
        $b = Builder::ctor(0x435885B5);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->rawBlob($private_);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.togglePeerTranslations#e47cb579 = Bool.
     */
    public function togglePeerTranslations(mixed $peer, bool $disabled = false): mixed
    {
        $flags = 0;
        if ($disabled) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xE47CB579);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.toggleSavedDialogPin#ac81bbde = Bool.
     */
    public function toggleSavedDialogPin(mixed $peer, bool $pinned = false): mixed
    {
        $flags = 0;
        if ($pinned) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xAC81BBDE);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveDialogPeer($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.toggleStickerSets#b5052fea = Bool.
     */
    public function toggleStickerSets(array $stickersets, bool $uninstall = false, bool $archive = false, bool $unarchive = false): mixed
    {
        $flags = 0;
        if ($uninstall) { $flags |= (1 << 0); }
        if ($archive) { $flags |= (1 << 1); }
        if ($unarchive) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xB5052FEA);
        $b->int($flags);
        $b->vector($stickersets);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.toggleSuggestedPostApproval#8107455c = Updates.
     */
    public function toggleSuggestedPostApproval(mixed $peer, int $msg_id, bool $reject = false, ?int $schedule_date = null, ?string $reject_comment = null): mixed
    {
        $flags = 0;
        if ($reject) { $flags |= (1 << 1); }
        if ($schedule_date !== null) { $flags |= (1 << 0); }
        if ($reject_comment !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x8107455C);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        if ($schedule_date !== null) { $b->int((int)$schedule_date); }
        if ($reject_comment !== null) { $b->string((string)$reject_comment); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.toggleTodoCompleted#d3e03124 = Updates.
     */
    public function toggleTodoCompleted(mixed $peer, int $msg_id, array $completed, array $incompleted): mixed
    {
        $b = Builder::ctor(0xD3E03124);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        $b->vectorInt($completed);
        $b->vectorInt($incompleted);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.transcribeAudio#269e9a49 = messages.TranscribedAudio.
     */
    public function transcribeAudio(mixed $peer, int $msg_id): mixed
    {
        $b = Builder::ctor(0x269E9A49);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.TranscribedAudio');
    }

    /**
     * messages.translateRichMessage#1a542004 = messages.TranslatedRichMessage.
     */
    public function translateRichMessage(string $to_lang, mixed $peer = null, ?array $id = null, ?array $text = null, ?string $tone = null): mixed
    {
        $flags = 0;
        if ($peer !== null) { $flags |= (1 << 0); }
        if ($id !== null) { $flags |= (1 << 0); }
        if ($text !== null) { $flags |= (1 << 1); }
        if ($tone !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x1A542004);
        $b->int($flags);
        if ($peer !== null) { $b->rawBlob($this->peerBlob($peer)); }
        if ($id !== null) { $b->vectorInt($id); }
        if ($text !== null) { $b->vector($text); }
        $b->string((string)$to_lang);
        if ($tone !== null) { $b->string((string)$tone); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.TranslatedRichMessage');
    }

    /**
     * messages.translateText#a5eec345 = messages.TranslatedText.
     */
    public function translateText(string $to_lang, mixed $peer = null, ?array $id = null, ?array $text = null, ?string $tone = null): mixed
    {
        $flags = 0;
        if ($peer !== null) { $flags |= (1 << 0); }
        if ($id !== null) { $flags |= (1 << 0); }
        if ($text !== null) { $flags |= (1 << 1); }
        if ($tone !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xA5EEC345);
        $b->int($flags);
        if ($peer !== null) { $b->rawBlob($this->peerBlob($peer)); }
        if ($id !== null) { $b->vectorInt($id); }
        if ($text !== null) { $b->vector($text); }
        $b->string((string)$to_lang);
        if ($tone !== null) { $b->string((string)$tone); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.TranslatedText');
    }

    /**
     * messages.uninstallStickerSet#f96e55de = Bool.
     */
    public function uninstallStickerSet(string $stickerset): mixed
    {
        $b = Builder::ctor(0xF96E55DE);
        $b->rawBlob($stickerset);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.unpinAllMessages#62dd747 = messages.AffectedHistory.
     */
    public function unpinAllMessages(mixed $peer, ?int $top_msg_id = null, mixed $saved_peer_id = null): mixed
    {
        $flags = 0;
        if ($top_msg_id !== null) { $flags |= (1 << 0); }
        if ($saved_peer_id !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x62DD747);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($top_msg_id !== null) { $b->int((int)$top_msg_id); }
        if ($saved_peer_id !== null) { $b->rawBlob($this->peerBlob($saved_peer_id)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedHistory');
    }

    /**
     * messages.updateDialogFilter#1ad4a04a = Bool.
     */
    public function updateDialogFilter(int $id, ?string $filter = null): mixed
    {
        $flags = 0;
        if ($filter !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x1AD4A04A);
        $b->int($flags);
        $b->int((int)$id);
        if ($filter !== null) { $b->rawBlob($filter); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.updateDialogFiltersOrder#c563c1e4 = Bool.
     */
    public function updateDialogFiltersOrder(array $order): mixed
    {
        $b = Builder::ctor(0xC563C1E4);
        $b->vectorInt($order);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.updatePinnedForumTopic#175df251 = Updates.
     */
    public function updatePinnedForumTopic(mixed $peer, int $topic_id, bool $pinned): mixed
    {
        $b = Builder::ctor(0x175DF251);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$topic_id);
        $b->bool((bool)$pinned);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.updatePinnedMessage#d2aaf7ec = Updates.
     */
    public function updatePinnedMessage(mixed $peer, int $id, bool $silent = false, bool $unpin = false, bool $pm_oneside = false): mixed
    {
        $flags = 0;
        if ($silent) { $flags |= (1 << 0); }
        if ($unpin) { $flags |= (1 << 1); }
        if ($pm_oneside) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xD2AAF7EC);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * messages.updateSavedReactionTag#60297dec = Bool.
     */
    public function updateSavedReactionTag(string $reaction, ?string $title = null): mixed
    {
        $flags = 0;
        if ($title !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x60297DEC);
        $b->int($flags);
        $b->rawBlob($reaction);
        if ($title !== null) { $b->string((string)$title); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * messages.uploadEncryptedFile#5057c497 = EncryptedFile.
     */
    public function uploadEncryptedFile(string $peer, string $file): mixed
    {
        $b = Builder::ctor(0x5057C497);
        $b->rawBlob($peer);
        $b->rawBlob($file);
        return Deserializer::parse($this->client->rpc($b->build()), 'EncryptedFile');
    }

    /**
     * messages.uploadImportedMedia#2a862092 = MessageMedia.
     */
    public function uploadImportedMedia(mixed $peer, int $import_id, string $file_name, string $media): mixed
    {
        $b = Builder::ctor(0x2A862092);
        $b->rawBlob($this->peerBlob($peer));
        $b->long((int)$import_id);
        $b->string((string)$file_name);
        $b->rawBlob($media);
        return Deserializer::parse($this->client->rpc($b->build()), 'MessageMedia');
    }

    /**
     * messages.uploadMedia#14967978 = MessageMedia.
     */
    public function uploadMedia(mixed $peer, string $media, ?string $business_connection_id = null): mixed
    {
        $flags = 0;
        if ($business_connection_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x14967978);
        $b->int($flags);
        if ($business_connection_id !== null) { $b->string((string)$business_connection_id); }
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($media);
        return Deserializer::parse($this->client->rpc($b->build()), 'MessageMedia');
    }

    /**
     * messages.viewSponsoredMessage#269e3643 = Bool.
     */
    public function viewSponsoredMessage(string $random_id): mixed
    {
        $b = Builder::ctor(0x269E3643);
        $b->string((string)$random_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }
}
