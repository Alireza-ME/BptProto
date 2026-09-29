<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every premium.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Premium extends Group
{

    /**
     * premium.applyBoost#6b7da746 = premium.MyBoosts.
     */
    public function applyBoost(mixed $peer, ?array $slots = null): mixed
    {
        $flags = 0;
        if ($slots !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x6B7DA746);
        $b->int($flags);
        if ($slots !== null) { $b->vectorInt($slots); }
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'premium.MyBoosts');
    }

    /**
     * premium.getBoostsList#60f67660 = premium.BoostsList.
     */
    public function getBoostsList(mixed $peer, string $offset, int $limit, bool $gifts = false): mixed
    {
        $flags = 0;
        if ($gifts) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x60F67660);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'premium.BoostsList');
    }

    /**
     * premium.getBoostsStatus#42f1f61 = premium.BoostsStatus.
     */
    public function getBoostsStatus(mixed $peer): mixed
    {
        $b = Builder::ctor(0x42F1F61);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'premium.BoostsStatus');
    }

    /**
     * premium.getMyBoosts#be77b4a = premium.MyBoosts.
     */
    public function getMyBoosts(): mixed
    {
        $b = Builder::ctor(0xBE77B4A);
        return Deserializer::parse($this->client->rpc($b->build()), 'premium.MyBoosts');
    }

    /**
     * premium.getUserBoosts#39854d1f = premium.BoostsList.
     */
    public function getUserBoosts(mixed $peer, mixed $user_id): mixed
    {
        $b = Builder::ctor(0x39854D1F);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peers()->resolveUser($user_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'premium.BoostsList');
    }
}
