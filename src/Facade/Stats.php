<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every stats.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Stats extends Group
{

    /**
     * stats.getBroadcastStats#ab42441a = stats.BroadcastStats.
     */
    public function getBroadcastStats(mixed $channel, bool $dark = false): mixed
    {
        $flags = 0;
        if ($dark) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xAB42441A);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($channel));
        return Deserializer::parse($this->client->rpc($b->build()), 'stats.BroadcastStats');
    }

    /**
     * stats.getMegagroupStats#dcdf8607 = stats.MegagroupStats.
     */
    public function getMegagroupStats(mixed $channel, bool $dark = false): mixed
    {
        $flags = 0;
        if ($dark) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xDCDF8607);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($channel));
        return Deserializer::parse($this->client->rpc($b->build()), 'stats.MegagroupStats');
    }

    /**
     * stats.getMessagePublicForwards#5f150144 = stats.PublicForwards.
     */
    public function getMessagePublicForwards(mixed $channel, int $msg_id, string $offset, int $limit): mixed
    {
        $b = Builder::ctor(0x5F150144);
        $b->rawBlob($this->channelBlob($channel));
        $b->int((int)$msg_id);
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'stats.PublicForwards');
    }

    /**
     * stats.getMessageStats#b6e0a3f5 = stats.MessageStats.
     */
    public function getMessageStats(mixed $channel, int $msg_id, bool $dark = false): mixed
    {
        $flags = 0;
        if ($dark) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xB6E0A3F5);
        $b->int($flags);
        $b->rawBlob($this->channelBlob($channel));
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'stats.MessageStats');
    }

    /**
     * stats.getPollStats#c27dfa68 = stats.PollStats.
     */
    public function getPollStats(mixed $peer, int $msg_id, bool $dark = false): mixed
    {
        $flags = 0;
        if ($dark) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xC27DFA68);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'stats.PollStats');
    }

    /**
     * stats.getStoryPublicForwards#a6437ef6 = stats.PublicForwards.
     */
    public function getStoryPublicForwards(mixed $peer, int $id, string $offset, int $limit): mixed
    {
        $b = Builder::ctor(0xA6437EF6);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'stats.PublicForwards');
    }

    /**
     * stats.getStoryStats#374fef40 = stats.StoryStats.
     */
    public function getStoryStats(mixed $peer, int $id, bool $dark = false): mixed
    {
        $flags = 0;
        if ($dark) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x374FEF40);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        return Deserializer::parse($this->client->rpc($b->build()), 'stats.StoryStats');
    }

    /**
     * stats.loadAsyncGraph#621d5fa0 = StatsGraph.
     */
    public function loadAsyncGraph(string $token, ?int $x = null): mixed
    {
        $flags = 0;
        if ($x !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x621D5FA0);
        $b->int($flags);
        $b->string((string)$token);
        if ($x !== null) { $b->long((int)$x); }
        return Deserializer::parse($this->client->rpc($b->build()), 'StatsGraph');
    }
}
