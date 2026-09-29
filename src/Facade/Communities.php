<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every communities.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Communities extends Group
{

    /**
     * communities.create#a63859ec = Updates.
     */
    public function create(string $title, mixed $peer, bool $hidden = false, ?string $about = null): mixed
    {
        $flags = 0;
        if ($hidden) { $flags |= (1 << 1); }
        if ($about !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xA63859EC);
        $b->int($flags);
        $b->string((string)$title);
        if ($about !== null) { $b->string((string)$about); }
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * communities.getJoinedCommunities#a663e830 = messages.Chats.
     */
    public function getJoinedCommunities(): mixed
    {
        $b = Builder::ctor(0xA663E830);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Chats');
    }

    /**
     * communities.getParticipantJoinedChats#f87eabab = communities.ParticipantJoinedChats.
     */
    public function getParticipantJoinedChats(mixed $community, mixed $participant): mixed
    {
        $b = Builder::ctor(0xF87EABAB);
        $b->rawBlob($this->channelBlob($community));
        $b->rawBlob($this->peerBlob($participant));
        return Deserializer::parse($this->client->rpc($b->build()), 'communities.ParticipantJoinedChats');
    }

    /**
     * communities.getPeerLinkRequests#93773344 = communities.PeerLinkRequests.
     */
    public function getPeerLinkRequests(mixed $community, string $offset, int $limit): mixed
    {
        $b = Builder::ctor(0x93773344);
        $b->rawBlob($this->channelBlob($community));
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'communities.PeerLinkRequests');
    }

    /**
     * communities.toggleAllPeerLinkRequestApproval#bfe3dd3d = Bool.
     */
    public function toggleAllPeerLinkRequestApproval(mixed $community, bool $reject = false): mixed
    {
        $flags = 0;
        if ($reject) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xBFE3DD3D);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($community));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * communities.toggleCommunityCollapsedInDialogs#d766e3ea = Updates.
     */
    public function toggleCommunityCollapsedInDialogs(mixed $community, bool $collapsed = false): mixed
    {
        $flags = 0;
        if ($collapsed) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xD766E3EA);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($community));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * communities.toggleParticipantBanned#9967ad0f = Bool.
     */
    public function toggleParticipantBanned(mixed $community, mixed $participant, bool $unban = false): mixed
    {
        $flags = 0;
        if ($unban) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x9967AD0F);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($community));
        $b->rawBlob($this->peerBlob($participant));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * communities.togglePeerLink#736dcfea = Bool.
     */
    public function togglePeerLink(mixed $community, mixed $peer, bool $visible = false, bool $hidden = false, bool $deleted = false): mixed
    {
        $flags = 0;
        if ($visible) { $flags |= (1 << 0); }
        if ($hidden) { $flags |= (1 << 1); }
        if ($deleted) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x736DCFEA);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($community));
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * communities.togglePeerLinkRequestApproval#8c8219a8 = Bool.
     */
    public function togglePeerLinkRequestApproval(mixed $community, mixed $peer, bool $reject = false): mixed
    {
        $flags = 0;
        if ($reject) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x8C8219A8);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($community));
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }
}
