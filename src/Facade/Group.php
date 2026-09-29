<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\BptProto;
use Bpt\Client;

/**
 * Shared base for the API facade groups.
 *
 * The groups hold the *surface* only: connection, the retry/recovery engine
 * and the peer cache live on {@see Client}, so every group can stay small and
 * focused on one namespace (messages, contacts, …).
 */
abstract class Group
{
    public function __construct(protected readonly Client $client)
    {
    }

    /** The grouped facade this group belongs to. */
    final protected function proto(): BptProto
    {
        return $this->client->proto();
    }

    /** Peer resolution group (shared by every destination-taking method). */
    final protected function peers(): Peers
    {
        return $this->proto()->peers;
    }

    final protected function peerBlob(mixed $peer): string
    {
        return $this->peers()->resolvePeer($peer);
    }

    final protected function channelBlob(mixed $channel): string
    {
        return $this->peers()->resolveChannel($channel);
    }
}
