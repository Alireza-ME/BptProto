<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every channels.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Channels extends Group
{

    /**
     * channels.checkSearchPostsFlood#22567115 = SearchPostsFlood.
     */
    public function checkSearchPostsFlood(?string $query = null): mixed
    {
        $flags = 0;
        if ($query !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x22567115);
        $b->int($flags);
        if ($query !== null) { $b->string((string)$query); }
        return Deserializer::parse($this->client->rpc($b->build()), 'SearchPostsFlood');
    }

    /**
     * channels.checkUsername#10e6bd2c = Bool.
     */
    public function checkUsername(mixed $channel, string $username): mixed
    {
        $b = Builder::ctor(0x10E6BD2C);
        $b->rawBlob($this->channelBlob($channel));
        $b->string((string)$username);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.convertToGigagroup#b290c69 = Updates.
     */
    public function convertToGigagroup(mixed $channel): mixed
    {
        $b = Builder::ctor(0xB290C69);
        $b->rawBlob($this->channelBlob($channel));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.createChannel#91006707 = Updates.
     */
    public function createChannel(string $title, string $about, bool $broadcast = false, bool $megagroup = false, bool $for_import = false, bool $forum = false, ?string $geo_point = null, ?string $address = null, ?int $ttl_period = null): mixed
    {
        $flags = 0;
        if ($broadcast) { $flags |= (1 << 0); }
        if ($megagroup) { $flags |= (1 << 1); }
        if ($for_import) { $flags |= (1 << 3); }
        if ($forum) { $flags |= (1 << 5); }
        if ($geo_point !== null) { $flags |= (1 << 2); }
        if ($address !== null) { $flags |= (1 << 2); }
        if ($ttl_period !== null) { $flags |= (1 << 4); }
        $b = Builder::ctor(0x91006707);
        $b->int($flags);
        $b->string((string)$title);
        $b->string((string)$about);
        if ($geo_point !== null) { $b->rawBlob($geo_point); }
        if ($address !== null) { $b->string((string)$address); }
        if ($ttl_period !== null) { $b->int((int)$ttl_period); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.deactivateAllUsernames#a245dd3 = Bool.
     */
    public function deactivateAllUsernames(mixed $channel): mixed
    {
        $b = Builder::ctor(0xA245DD3);
        $b->rawBlob($this->channelBlob($channel));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.deleteChannel#c0111fe3 = Updates.
     */
    public function deleteChannel(mixed $channel): mixed
    {
        $b = Builder::ctor(0xC0111FE3);
        $b->rawBlob($this->channelBlob($channel));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.deleteHistory#9baa9647 = Updates.
     */
    public function deleteHistory(mixed $channel, int $max_id, bool $for_everyone = false): mixed
    {
        $flags = 0;
        if ($for_everyone) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x9BAA9647);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($channel));
        $b->int((int)$max_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.deleteMessages#84c1fd4e = messages.AffectedMessages.
     */
    public function deleteMessages(mixed $channel, array $id): mixed
    {
        $b = Builder::ctor(0x84C1FD4E);
        $b->rawBlob($this->channelBlob($channel));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedMessages');
    }

    /**
     * channels.deleteParticipantHistory#367544db = messages.AffectedHistory.
     */
    public function deleteParticipantHistory(mixed $channel, mixed $participant): mixed
    {
        $b = Builder::ctor(0x367544DB);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($this->peerBlob($participant));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.AffectedHistory');
    }

    /**
     * channels.editAdmin#9a98ad68 = Updates.
     */
    public function editAdmin(mixed $channel, mixed $user_id, string $admin_rights, ?string $rank = null): mixed
    {
        $flags = 0;
        if ($rank !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x9A98AD68);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->rawBlob($admin_rights);
        if ($rank !== null) { $b->string((string)$rank); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.editBanned#96e6cd81 = Updates.
     */
    public function editBanned(mixed $channel, mixed $participant, string $banned_rights): mixed
    {
        $b = Builder::ctor(0x96E6CD81);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($this->peerBlob($participant));
        $b->rawBlob($banned_rights);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.editLocation#58e63f6d = Bool.
     */
    public function editLocation(mixed $channel, string $geo_point, string $address): mixed
    {
        $b = Builder::ctor(0x58E63F6D);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($geo_point);
        $b->string((string)$address);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.editPhoto#f12e57c9 = Updates.
     */
    public function editPhoto(mixed $channel, string $photo): mixed
    {
        $b = Builder::ctor(0xF12E57C9);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($photo);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.editTitle#566decd0 = Updates.
     */
    public function editTitle(mixed $channel, string $title): mixed
    {
        $b = Builder::ctor(0x566DECD0);
        $b->rawBlob($this->channelBlob($channel));
        $b->string((string)$title);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.exportMessageLink#e63fadeb = ExportedMessageLink.
     */
    public function exportMessageLink(mixed $channel, int $id, bool $grouped = false, bool $thread = false): mixed
    {
        $flags = 0;
        if ($grouped) { $flags |= (1 << 0); }
        if ($thread) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xE63FADEB);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($channel));
        $b->int((int)$id);
        return Deserializer::parse($this->client->rpc($b->build()), 'ExportedMessageLink');
    }

    /**
     * channels.getAdminLog#33ddf480 = channels.AdminLogResults.
     */
    public function getAdminLog(mixed $channel, string $q, int $max_id, int $min_id, int $limit, ?string $events_filter = null, ?array $admins = null): mixed
    {
        $flags = 0;
        if ($events_filter !== null) { $flags |= (1 << 0); }
        if ($admins !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x33DDF480);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($channel));
        $b->string((string)$q);
        if ($events_filter !== null) { $b->rawBlob($events_filter); }
        if ($admins !== null) { $b->vector(array_map(fn($x) => $this->peers()->resolveUser($x), $admins)); }
        $b->long((int)$max_id);
        $b->long((int)$min_id);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'channels.AdminLogResults');
    }

    /**
     * channels.getAdminedPublicChannels#f8b036af = messages.Chats.
     */
    public function getAdminedPublicChannels(bool $by_location = false, bool $check_limit = false, bool $for_personal = false, bool $for_community_peer = false): mixed
    {
        $flags = 0;
        if ($by_location) { $flags |= (1 << 0); }
        if ($check_limit) { $flags |= (1 << 1); }
        if ($for_personal) { $flags |= (1 << 2); }
        if ($for_community_peer) { $flags |= (1 << 3); }
        $b = Builder::ctor(0xF8B036AF);
        $b->int($flags);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Chats');
    }

    /**
     * channels.getChannelRecommendations#25a71742 = messages.Chats.
     */
    public function getChannelRecommendations(mixed $channel = null): mixed
    {
        $flags = 0;
        if ($channel !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x25A71742);
        $b->int($flags);
        if ($channel !== null) { $b->rawBlob($this->channelBlob($channel)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Chats');
    }

    /**
     * channels.getChannels#a7f6bbb = messages.Chats.
     */
    public function getChannels(array $id): mixed
    {
        $b = Builder::ctor(0xA7F6BBB);
        $b->vector(array_map(fn($x) => $this->channelBlob($x), $id));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Chats');
    }

    /**
     * channels.getFullChannel#8736a09 = messages.ChatFull.
     */
    public function getFullChannel(mixed $channel): mixed
    {
        $b = Builder::ctor(0x8736A09);
        $b->rawBlob($this->channelBlob($channel));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ChatFull');
    }

    /**
     * channels.getGroupsForDiscussion#f5dad378 = messages.Chats.
     */
    public function getGroupsForDiscussion(): mixed
    {
        $b = Builder::ctor(0xF5DAD378);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Chats');
    }

    /**
     * channels.getInactiveChannels#11e831ee = messages.InactiveChats.
     */
    public function getInactiveChannels(): mixed
    {
        $b = Builder::ctor(0x11E831EE);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.InactiveChats');
    }

    /**
     * channels.getLeftChannels#8341ecc0 = messages.Chats.
     */
    public function getLeftChannels(int $offset): mixed
    {
        $b = Builder::ctor(0x8341ECC0);
        $b->int((int)$offset);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Chats');
    }

    /**
     * channels.getMessageAuthor#ece2a0e6 = User.
     */
    public function getMessageAuthor(mixed $channel, int $id): mixed
    {
        $b = Builder::ctor(0xECE2A0E6);
        $b->rawBlob($this->channelBlob($channel));
        $b->int((int)$id);
        return Deserializer::parse($this->client->rpc($b->build()), 'User');
    }

    /**
     * channels.getMessages#ad8c9a23 = messages.Messages.
     */
    public function getMessages(mixed $channel, array $id): mixed
    {
        $b = Builder::ctor(0xAD8C9A23);
        $b->rawBlob($this->channelBlob($channel));
        $b->vector($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * channels.getParticipant#a0ab6cc6 = channels.ChannelParticipant.
     */
    public function getParticipant(mixed $channel, mixed $participant): mixed
    {
        $b = Builder::ctor(0xA0AB6CC6);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($this->peerBlob($participant));
        return Deserializer::parse($this->client->rpc($b->build()), 'channels.ChannelParticipant');
    }

    /**
     * channels.getParticipants#77ced9d0 = channels.ChannelParticipants.
     */
    public function getParticipants(mixed $channel, string $filter, int $offset, int $limit, int $hash): mixed
    {
        $b = Builder::ctor(0x77CED9D0);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($filter);
        $b->int((int)$offset);
        $b->int((int)$limit);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'channels.ChannelParticipants');
    }

    /**
     * channels.getSendAs#e785a43f = channels.SendAsPeers.
     */
    public function getSendAs(mixed $peer, bool $for_paid_reactions = false, bool $for_live_stories = false): mixed
    {
        $flags = 0;
        if ($for_paid_reactions) { $flags |= (1 << 0); }
        if ($for_live_stories) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xE785A43F);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'channels.SendAsPeers');
    }

    /**
     * channels.inviteToChannel#c9e33d54 = messages.InvitedUsers.
     */
    public function inviteToChannel(mixed $channel, array $users): mixed
    {
        $b = Builder::ctor(0xC9E33D54);
        $b->rawBlob($this->channelBlob($channel));
        $b->vector(array_map(fn($x) => $this->peers()->resolveUser($x), $users));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.InvitedUsers');
    }

    /**
     * channels.joinChannel#7f6a1e22 = messages.ChatInviteJoinResult.
     */
    public function joinChannel(mixed $channel): mixed
    {
        $b = Builder::ctor(0x7F6A1E22);
        $b->rawBlob($this->channelBlob($channel));
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.ChatInviteJoinResult');
    }

    /**
     * channels.leaveChannel#f836aa95 = Updates.
     */
    public function leaveChannel(mixed $channel): mixed
    {
        $b = Builder::ctor(0xF836AA95);
        $b->rawBlob($this->channelBlob($channel));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.readHistory#cc104937 = Bool.
     */
    public function readHistory(mixed $channel, int $max_id): mixed
    {
        $b = Builder::ctor(0xCC104937);
        $b->rawBlob($this->channelBlob($channel));
        $b->int((int)$max_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.readMessageContents#eab5dc38 = Bool.
     */
    public function readMessageContents(mixed $channel, array $id): mixed
    {
        $b = Builder::ctor(0xEAB5DC38);
        $b->rawBlob($this->channelBlob($channel));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.reorderUsernames#b45ced1d = Bool.
     */
    public function reorderUsernames(mixed $channel, array $order): mixed
    {
        $b = Builder::ctor(0xB45CED1D);
        $b->rawBlob($this->channelBlob($channel));
        $b->vectorString($order);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.reportAntiSpamFalsePositive#a850a693 = Bool.
     */
    public function reportAntiSpamFalsePositive(mixed $channel, int $msg_id): mixed
    {
        $b = Builder::ctor(0xA850A693);
        $b->rawBlob($this->channelBlob($channel));
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.reportSpam#f44a8315 = Bool.
     */
    public function reportSpam(mixed $channel, mixed $participant, array $id): mixed
    {
        $b = Builder::ctor(0xF44A8315);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($this->peerBlob($participant));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.restrictSponsoredMessages#9ae91519 = Updates.
     */
    public function restrictSponsoredMessages(mixed $channel, bool $restricted): mixed
    {
        $b = Builder::ctor(0x9AE91519);
        $b->rawBlob($this->channelBlob($channel));
        $b->bool((bool)$restricted);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.searchPosts#f2c4f24d = messages.Messages.
     */
    public function searchPosts(int $offset_rate, mixed $offset_peer, int $offset_id, int $limit, ?string $hashtag = null, ?string $query = null, ?int $allow_paid_stars = null): mixed
    {
        $flags = 0;
        if ($hashtag !== null) { $flags |= (1 << 0); }
        if ($query !== null) { $flags |= (1 << 1); }
        if ($allow_paid_stars !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xF2C4F24D);
        $b->int($flags);
        if ($hashtag !== null) { $b->string((string)$hashtag); }
        if ($query !== null) { $b->string((string)$query); }
        $b->int((int)$offset_rate);
        $b->rawBlob($this->peerBlob($offset_peer));
        $b->int((int)$offset_id);
        $b->int((int)$limit);
        if ($allow_paid_stars !== null) { $b->long((int)$allow_paid_stars); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Messages');
    }

    /**
     * channels.setBoostsToUnblockRestrictions#ad399cee = Updates.
     */
    public function setBoostsToUnblockRestrictions(mixed $channel, int $boosts): mixed
    {
        $b = Builder::ctor(0xAD399CEE);
        $b->rawBlob($this->channelBlob($channel));
        $b->int((int)$boosts);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.setDiscussionGroup#40582bb2 = Bool.
     */
    public function setDiscussionGroup(mixed $broadcast, mixed $group): mixed
    {
        $b = Builder::ctor(0x40582BB2);
        $b->rawBlob($this->channelBlob($broadcast));
        $b->rawBlob($this->channelBlob($group));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.setEmojiStickers#3cd930b7 = Bool.
     */
    public function setEmojiStickers(mixed $channel, string $stickerset): mixed
    {
        $b = Builder::ctor(0x3CD930B7);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($stickerset);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.setMainProfileTab#3583fcb1 = Bool.
     */
    public function setMainProfileTab(mixed $channel, string $tab): mixed
    {
        $b = Builder::ctor(0x3583FCB1);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($tab);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.setStickers#ea8ca4f9 = Bool.
     */
    public function setStickers(mixed $channel, string $stickerset): mixed
    {
        $b = Builder::ctor(0xEA8CA4F9);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($stickerset);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.toggleAntiSpam#68f3e4eb = Updates.
     */
    public function toggleAntiSpam(mixed $channel, bool $enabled): mixed
    {
        $b = Builder::ctor(0x68F3E4EB);
        $b->rawBlob($this->channelBlob($channel));
        $b->bool((bool)$enabled);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.toggleAutotranslation#167fc0a1 = Updates.
     */
    public function toggleAutotranslation(mixed $channel, bool $enabled): mixed
    {
        $b = Builder::ctor(0x167FC0A1);
        $b->rawBlob($this->channelBlob($channel));
        $b->bool((bool)$enabled);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.toggleForum#3ff75734 = Updates.
     */
    public function toggleForum(mixed $channel, bool $enabled, bool $tabs): mixed
    {
        $b = Builder::ctor(0x3FF75734);
        $b->rawBlob($this->channelBlob($channel));
        $b->bool((bool)$enabled);
        $b->bool((bool)$tabs);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.toggleJoinRequest#ecc2618 = Updates.
     */
    public function toggleJoinRequest(mixed $channel, bool $enabled, bool $apply_to_invites = false, mixed $guard_bot = null): mixed
    {
        $flags = 0;
        if ($apply_to_invites) { $flags |= (1 << 1); }
        if ($guard_bot !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xECC2618);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($channel));
        $b->bool((bool)$enabled);
        if ($guard_bot !== null) { $b->rawBlob($this->peers()->resolveUser($guard_bot)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.toggleJoinToSend#e4cb9580 = Updates.
     */
    public function toggleJoinToSend(mixed $channel, bool $enabled): mixed
    {
        $b = Builder::ctor(0xE4CB9580);
        $b->rawBlob($this->channelBlob($channel));
        $b->bool((bool)$enabled);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.toggleParticipantsHidden#6a6e7854 = Updates.
     */
    public function toggleParticipantsHidden(mixed $channel, bool $enabled): mixed
    {
        $b = Builder::ctor(0x6A6E7854);
        $b->rawBlob($this->channelBlob($channel));
        $b->bool((bool)$enabled);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.togglePreHistoryHidden#eabbb94c = Updates.
     */
    public function togglePreHistoryHidden(mixed $channel, bool $enabled): mixed
    {
        $b = Builder::ctor(0xEABBB94C);
        $b->rawBlob($this->channelBlob($channel));
        $b->bool((bool)$enabled);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.toggleSignatures#418d549c = Updates.
     */
    public function toggleSignatures(mixed $channel, bool $signatures_enabled = false, bool $profiles_enabled = false): mixed
    {
        $flags = 0;
        if ($signatures_enabled) { $flags |= (1 << 0); }
        if ($profiles_enabled) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x418D549C);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($channel));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.toggleSlowMode#edd49ef0 = Updates.
     */
    public function toggleSlowMode(mixed $channel, int $seconds): mixed
    {
        $b = Builder::ctor(0xEDD49EF0);
        $b->rawBlob($this->channelBlob($channel));
        $b->int((int)$seconds);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.toggleUsername#50f24105 = Bool.
     */
    public function toggleUsername(mixed $channel, string $username, bool $active): mixed
    {
        $b = Builder::ctor(0x50F24105);
        $b->rawBlob($this->channelBlob($channel));
        $b->string((string)$username);
        $b->bool((bool)$active);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * channels.toggleViewForumAsMessages#9738bb15 = Updates.
     */
    public function toggleViewForumAsMessages(mixed $channel, bool $enabled): mixed
    {
        $b = Builder::ctor(0x9738BB15);
        $b->rawBlob($this->channelBlob($channel));
        $b->bool((bool)$enabled);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.updateColor#d8aa3671 = Updates.
     */
    public function updateColor(mixed $channel, bool $for_profile = false, ?int $color = null, ?int $background_emoji_id = null): mixed
    {
        $flags = 0;
        if ($for_profile) { $flags |= (1 << 1); }
        if ($color !== null) { $flags |= (1 << 2); }
        if ($background_emoji_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xD8AA3671);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($channel));
        if ($color !== null) { $b->int((int)$color); }
        if ($background_emoji_id !== null) { $b->long((int)$background_emoji_id); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.updateEmojiStatus#f0d3e6a8 = Updates.
     */
    public function updateEmojiStatus(mixed $channel, string $emoji_status): mixed
    {
        $b = Builder::ctor(0xF0D3E6A8);
        $b->rawBlob($this->channelBlob($channel));
        $b->rawBlob($emoji_status);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.updatePaidMessagesPrice#4b12327b = Updates.
     */
    public function updatePaidMessagesPrice(mixed $channel, int $send_paid_messages_stars, bool $broadcast_messages_allowed = false): mixed
    {
        $flags = 0;
        if ($broadcast_messages_allowed) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x4B12327B);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($channel));
        $b->long((int)$send_paid_messages_stars);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * channels.updateUsername#3514b3de = Bool.
     */
    public function updateUsername(mixed $channel, string $username): mixed
    {
        $b = Builder::ctor(0x3514B3DE);
        $b->rawBlob($this->channelBlob($channel));
        $b->string((string)$username);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }
}
