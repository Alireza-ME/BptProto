<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every phone.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Phone extends Group
{

    /**
     * phone.acceptCall#3bd2b4a0 = phone.PhoneCall.
     */
    public function acceptCall(string $peer, string $g_b, string $protocol): mixed
    {
        $b = Builder::ctor(0x3BD2B4A0);
        $b->rawBlob($peer);
        $b->string((string)$g_b);
        $b->rawBlob($protocol);
        return Deserializer::parse($this->client->rpc($b->build()), 'phone.PhoneCall');
    }

    /**
     * phone.checkGroupCall#b59cf977 = Vector<int>.
     */
    public function checkGroupCall(string $call, array $sources): mixed
    {
        $b = Builder::ctor(0xB59CF977);
        $b->rawBlob($call);
        $b->vectorInt($sources);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<int>');
    }

    /**
     * phone.confirmCall#2efe1722 = phone.PhoneCall.
     */
    public function confirmCall(string $peer, string $g_a, int $key_fingerprint, string $protocol): mixed
    {
        $b = Builder::ctor(0x2EFE1722);
        $b->rawBlob($peer);
        $b->string((string)$g_a);
        $b->long((int)$key_fingerprint);
        $b->rawBlob($protocol);
        return Deserializer::parse($this->client->rpc($b->build()), 'phone.PhoneCall');
    }

    /**
     * phone.createConferenceCall#7d0444bb = Updates.
     */
    public function createConferenceCall(int $random_id, bool $muted = false, bool $video_stopped = false, bool $join = false, ?string $public_key = null, ?string $block = null, ?string $params = null): mixed
    {
        $flags = 0;
        if ($muted) { $flags |= (1 << 0); }
        if ($video_stopped) { $flags |= (1 << 2); }
        if ($join) { $flags |= (1 << 3); }
        if ($public_key !== null) { $flags |= (1 << 3); }
        if ($block !== null) { $flags |= (1 << 3); }
        if ($params !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x7D0444BB);
        $b->int($flags);
        $b->int((int)$random_id);
        if ($public_key !== null) { $b->rawBlob($public_key); }
        if ($block !== null) { $b->string((string)$block); }
        if ($params !== null) { $b->rawBlob($params); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.createGroupCall#48cdc6d8 = Updates.
     */
    public function createGroupCall(mixed $peer, int $random_id, bool $rtmp_stream = false, ?string $title = null, ?int $schedule_date = null): mixed
    {
        $flags = 0;
        if ($rtmp_stream) { $flags |= (1 << 2); }
        if ($title !== null) { $flags |= (1 << 0); }
        if ($schedule_date !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x48CDC6D8);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$random_id);
        if ($title !== null) { $b->string((string)$title); }
        if ($schedule_date !== null) { $b->int((int)$schedule_date); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.declineConferenceCallInvite#3c479971 = Updates.
     */
    public function declineConferenceCallInvite(int $msg_id): mixed
    {
        $b = Builder::ctor(0x3C479971);
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.deleteConferenceCallParticipants#8ca60525 = Updates.
     */
    public function deleteConferenceCallParticipants(string $call, array $ids, string $block, bool $only_left = false, bool $kick = false): mixed
    {
        $flags = 0;
        if ($only_left) { $flags |= (1 << 0); }
        if ($kick) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x8CA60525);
        $b->int($flags);
        $b->rawBlob($call);
        $b->vectorLong($ids);
        $b->string((string)$block);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.deleteGroupCallMessages#f64f54f7 = Updates.
     */
    public function deleteGroupCallMessages(string $call, array $messages, bool $report_spam = false): mixed
    {
        $flags = 0;
        if ($report_spam) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xF64F54F7);
        $b->int($flags);
        $b->rawBlob($call);
        $b->vectorInt($messages);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.deleteGroupCallParticipantMessages#1dbfeca0 = Updates.
     */
    public function deleteGroupCallParticipantMessages(string $call, mixed $participant, bool $report_spam = false): mixed
    {
        $flags = 0;
        if ($report_spam) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x1DBFECA0);
        $b->int($flags);
        $b->rawBlob($call);
        $b->rawBlob($this->peerBlob($participant));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.discardCall#b2cbc1c0 = Updates.
     */
    public function discardCall(string $peer, int $duration, string $reason, int $connection_id, bool $video = false): mixed
    {
        $flags = 0;
        if ($video) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xB2CBC1C0);
        $b->int($flags);
        $b->rawBlob($peer);
        $b->int((int)$duration);
        $b->rawBlob($reason);
        $b->long((int)$connection_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.discardGroupCall#7a777135 = Updates.
     */
    public function discardGroupCall(string $call): mixed
    {
        $b = Builder::ctor(0x7A777135);
        $b->rawBlob($call);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.editGroupCallParticipant#a5273abf = Updates.
     */
    public function editGroupCallParticipant(string $call, mixed $participant, ?bool $muted = null, ?int $volume = null, ?bool $raise_hand = null, ?bool $video_stopped = null, ?bool $video_paused = null, ?bool $presentation_paused = null): mixed
    {
        $flags = 0;
        if ($muted !== null) { $flags |= (1 << 0); }
        if ($volume !== null) { $flags |= (1 << 1); }
        if ($raise_hand !== null) { $flags |= (1 << 2); }
        if ($video_stopped !== null) { $flags |= (1 << 3); }
        if ($video_paused !== null) { $flags |= (1 << 4); }
        if ($presentation_paused !== null) { $flags |= (1 << 5); }
        $b = Builder::ctor(0xA5273ABF);
        $b->int($flags);
        $b->rawBlob($call);
        $b->rawBlob($this->peerBlob($participant));
        if ($muted !== null) { $b->bool((bool)$muted); }
        if ($volume !== null) { $b->int((int)$volume); }
        if ($raise_hand !== null) { $b->bool((bool)$raise_hand); }
        if ($video_stopped !== null) { $b->bool((bool)$video_stopped); }
        if ($video_paused !== null) { $b->bool((bool)$video_paused); }
        if ($presentation_paused !== null) { $b->bool((bool)$presentation_paused); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.editGroupCallTitle#1ca6ac0a = Updates.
     */
    public function editGroupCallTitle(string $call, string $title): mixed
    {
        $b = Builder::ctor(0x1CA6AC0A);
        $b->rawBlob($call);
        $b->string((string)$title);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.exportGroupCallInvite#e6aa647f = phone.ExportedGroupCallInvite.
     */
    public function exportGroupCallInvite(string $call, bool $can_self_unmute = false): mixed
    {
        $flags = 0;
        if ($can_self_unmute) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xE6AA647F);
        $b->int($flags);
        $b->rawBlob($call);
        return Deserializer::parse($this->client->rpc($b->build()), 'phone.ExportedGroupCallInvite');
    }

    /**
     * phone.getCallConfig#55451fa9 = DataJSON.
     */
    public function getCallConfig(): mixed
    {
        $b = Builder::ctor(0x55451FA9);
        return Deserializer::parse($this->client->rpc($b->build()), 'DataJSON');
    }

    /**
     * phone.getGroupCall#41845db = phone.GroupCall.
     */
    public function getGroupCall(string $call, int $limit): mixed
    {
        $b = Builder::ctor(0x41845DB);
        $b->rawBlob($call);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'phone.GroupCall');
    }

    /**
     * phone.getGroupCallChainBlocks#ee9f88a6 = Updates.
     */
    public function getGroupCallChainBlocks(string $call, int $sub_chain_id, int $offset, int $limit): mixed
    {
        $b = Builder::ctor(0xEE9F88A6);
        $b->rawBlob($call);
        $b->int((int)$sub_chain_id);
        $b->int((int)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.getGroupCallJoinAs#ef7c213a = phone.JoinAsPeers.
     */
    public function getGroupCallJoinAs(mixed $peer): mixed
    {
        $b = Builder::ctor(0xEF7C213A);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'phone.JoinAsPeers');
    }

    /**
     * phone.getGroupCallStars#6f636302 = phone.GroupCallStars.
     */
    public function getGroupCallStars(string $call): mixed
    {
        $b = Builder::ctor(0x6F636302);
        $b->rawBlob($call);
        return Deserializer::parse($this->client->rpc($b->build()), 'phone.GroupCallStars');
    }

    /**
     * phone.getGroupCallStreamChannels#1ab21940 = phone.GroupCallStreamChannels.
     */
    public function getGroupCallStreamChannels(string $call): mixed
    {
        $b = Builder::ctor(0x1AB21940);
        $b->rawBlob($call);
        return Deserializer::parse($this->client->rpc($b->build()), 'phone.GroupCallStreamChannels');
    }

    /**
     * phone.getGroupCallStreamRtmpUrl#5af4c73a = phone.GroupCallStreamRtmpUrl.
     */
    public function getGroupCallStreamRtmpUrl(mixed $peer, bool $revoke, bool $live_story = false): mixed
    {
        $flags = 0;
        if ($live_story) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x5AF4C73A);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->bool((bool)$revoke);
        return Deserializer::parse($this->client->rpc($b->build()), 'phone.GroupCallStreamRtmpUrl');
    }

    /**
     * phone.getGroupParticipants#c558d8ab = phone.GroupParticipants.
     */
    public function getGroupParticipants(string $call, array $ids, array $sources, string $offset, int $limit): mixed
    {
        $b = Builder::ctor(0xC558D8AB);
        $b->rawBlob($call);
        $b->vector(array_map(fn($x) => $this->peerBlob($x), $ids));
        $b->vectorInt($sources);
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'phone.GroupParticipants');
    }

    /**
     * phone.inviteConferenceCallParticipant#bcf22685 = Updates.
     */
    public function inviteConferenceCallParticipant(string $call, mixed $user_id, bool $video = false): mixed
    {
        $flags = 0;
        if ($video) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xBCF22685);
        $b->int($flags);
        $b->rawBlob($call);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.inviteToGroupCall#7b393160 = Updates.
     */
    public function inviteToGroupCall(string $call, array $users): mixed
    {
        $b = Builder::ctor(0x7B393160);
        $b->rawBlob($call);
        $b->vector(array_map(fn($x) => $this->peers()->resolveUser($x), $users));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.joinGroupCall#8fb53057 = Updates.
     */
    public function joinGroupCall(string $call, mixed $join_as, string $params, bool $muted = false, bool $video_stopped = false, ?string $invite_hash = null, ?string $public_key = null, ?string $block = null): mixed
    {
        $flags = 0;
        if ($muted) { $flags |= (1 << 0); }
        if ($video_stopped) { $flags |= (1 << 2); }
        if ($invite_hash !== null) { $flags |= (1 << 1); }
        if ($public_key !== null) { $flags |= (1 << 3); }
        if ($block !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x8FB53057);
        $b->int($flags);
        $b->rawBlob($call);
        $b->rawBlob($this->peerBlob($join_as));
        if ($invite_hash !== null) { $b->string((string)$invite_hash); }
        if ($public_key !== null) { $b->rawBlob($public_key); }
        if ($block !== null) { $b->string((string)$block); }
        $b->rawBlob($params);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.joinGroupCallPresentation#cbea6bc4 = Updates.
     */
    public function joinGroupCallPresentation(string $call, string $params): mixed
    {
        $b = Builder::ctor(0xCBEA6BC4);
        $b->rawBlob($call);
        $b->rawBlob($params);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.leaveGroupCall#500377f9 = Updates.
     */
    public function leaveGroupCall(string $call, int $source): mixed
    {
        $b = Builder::ctor(0x500377F9);
        $b->rawBlob($call);
        $b->int((int)$source);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.leaveGroupCallPresentation#1c50d144 = Updates.
     */
    public function leaveGroupCallPresentation(string $call): mixed
    {
        $b = Builder::ctor(0x1C50D144);
        $b->rawBlob($call);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.receivedCall#17d54f61 = Bool.
     */
    public function receivedCall(string $peer): mixed
    {
        $b = Builder::ctor(0x17D54F61);
        $b->rawBlob($peer);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * phone.requestCall#42ff96ed = phone.PhoneCall.
     */
    public function requestCall(mixed $user_id, int $random_id, string $g_a_hash, string $protocol, bool $video = false): mixed
    {
        $flags = 0;
        if ($video) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x42FF96ED);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->int((int)$random_id);
        $b->string((string)$g_a_hash);
        $b->rawBlob($protocol);
        return Deserializer::parse($this->client->rpc($b->build()), 'phone.PhoneCall');
    }

    /**
     * phone.saveCallDebug#277add7e = Bool.
     */
    public function saveCallDebug(string $peer, string $debug): mixed
    {
        $b = Builder::ctor(0x277ADD7E);
        $b->rawBlob($peer);
        $b->rawBlob($debug);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * phone.saveCallLog#41248786 = Bool.
     */
    public function saveCallLog(string $peer, string $file): mixed
    {
        $b = Builder::ctor(0x41248786);
        $b->rawBlob($peer);
        $b->rawBlob($file);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * phone.saveDefaultGroupCallJoinAs#575e1f8c = Bool.
     */
    public function saveDefaultGroupCallJoinAs(mixed $peer, mixed $join_as): mixed
    {
        $b = Builder::ctor(0x575E1F8C);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peerBlob($join_as));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * phone.saveDefaultSendAs#4167add1 = Bool.
     */
    public function saveDefaultSendAs(string $call, mixed $send_as): mixed
    {
        $b = Builder::ctor(0x4167ADD1);
        $b->rawBlob($call);
        $b->rawBlob($this->peerBlob($send_as));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * phone.sendConferenceCallBroadcast#c6701900 = Updates.
     */
    public function sendConferenceCallBroadcast(string $call, string $block): mixed
    {
        $b = Builder::ctor(0xC6701900);
        $b->rawBlob($call);
        $b->string((string)$block);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.sendGroupCallEncryptedMessage#e5afa56d = Bool.
     */
    public function sendGroupCallEncryptedMessage(string $call, string $encrypted_message): mixed
    {
        $b = Builder::ctor(0xE5AFA56D);
        $b->rawBlob($call);
        $b->string((string)$encrypted_message);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * phone.sendGroupCallMessage#b1d11410 = Updates.
     */
    public function sendGroupCallMessage(string $call, int $random_id, string $message, ?int $allow_paid_stars = null, mixed $send_as = null): mixed
    {
        $flags = 0;
        if ($allow_paid_stars !== null) { $flags |= (1 << 0); }
        if ($send_as !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xB1D11410);
        $b->int($flags);
        $b->rawBlob($call);
        $b->long((int)$random_id);
        $b->rawBlob($message);
        if ($allow_paid_stars !== null) { $b->long((int)$allow_paid_stars); }
        if ($send_as !== null) { $b->rawBlob($this->peerBlob($send_as)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.sendSignalingData#ff7a9383 = Bool.
     */
    public function sendSignalingData(string $peer, string $data): mixed
    {
        $b = Builder::ctor(0xFF7A9383);
        $b->rawBlob($peer);
        $b->string((string)$data);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * phone.setCallRating#59ead627 = Updates.
     */
    public function setCallRating(string $peer, int $rating, string $comment, bool $user_initiative = false): mixed
    {
        $flags = 0;
        if ($user_initiative) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x59EAD627);
        $b->int($flags);
        $b->rawBlob($peer);
        $b->int((int)$rating);
        $b->string((string)$comment);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.startScheduledGroupCall#5680e342 = Updates.
     */
    public function startScheduledGroupCall(string $call): mixed
    {
        $b = Builder::ctor(0x5680E342);
        $b->rawBlob($call);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.toggleGroupCallRecord#f128c708 = Updates.
     */
    public function toggleGroupCallRecord(string $call, bool $start = false, bool $video = false, ?string $title = null, ?bool $video_portrait = null): mixed
    {
        $flags = 0;
        if ($start) { $flags |= (1 << 0); }
        if ($video) { $flags |= (1 << 2); }
        if ($title !== null) { $flags |= (1 << 1); }
        if ($video_portrait !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xF128C708);
        $b->int($flags);
        $b->rawBlob($call);
        if ($title !== null) { $b->string((string)$title); }
        if ($video_portrait !== null) { $b->bool((bool)$video_portrait); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.toggleGroupCallSettings#974392f2 = Updates.
     */
    public function toggleGroupCallSettings(string $call, bool $reset_invite_hash = false, ?bool $join_muted = null, ?bool $messages_enabled = null, ?int $send_paid_messages_stars = null): mixed
    {
        $flags = 0;
        if ($reset_invite_hash) { $flags |= (1 << 1); }
        if ($join_muted !== null) { $flags |= (1 << 0); }
        if ($messages_enabled !== null) { $flags |= (1 << 2); }
        if ($send_paid_messages_stars !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x974392F2);
        $b->int($flags);
        $b->rawBlob($call);
        if ($join_muted !== null) { $b->bool((bool)$join_muted); }
        if ($messages_enabled !== null) { $b->bool((bool)$messages_enabled); }
        if ($send_paid_messages_stars !== null) { $b->long((int)$send_paid_messages_stars); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * phone.toggleGroupCallStartSubscription#219c34e6 = Updates.
     */
    public function toggleGroupCallStartSubscription(string $call, bool $subscribed): mixed
    {
        $b = Builder::ctor(0x219C34E6);
        $b->rawBlob($call);
        $b->bool((bool)$subscribed);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }
}
