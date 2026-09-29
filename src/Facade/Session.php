<?php

declare(strict_types=1);

namespace Bpt\Facade;

/** Connection, DC and key lifecycle. → `$proto->session` */
final class Session extends Group
{
    public function dc(): int
    {
        return $this->client->getDcId();
    }

    public function setDc(int $dcId): void
    {
        $this->client->setDcId($dcId);
    }

    /** 256-byte auth key (connects first). */
    public function key(): string
    {
        return $this->client->authKey();
    }

    /** 8-byte auth_key_id. */
    public function keyId(): string
    {
        return $this->client->authKeyId();
    }

    /** help.getNearestDc → ['country','nearest','this_dc']. */
    public function nearestDc(): array
    {
        return $this->client->getNearestDc();
    }

    /** Delete key+salt+session for current DC (fresh handshake next time). */
    public function purgeAuth(): void
    {
        $this->client->purgeDcAuth();
    }

    /** Keep the key, drop the saved session_id. */
    public function dropSession(): void
    {
        $this->client->dropSavedSession();
    }

    /** Persist session state, close socket, release lock. */
    public function close(): void
    {
        $this->client->close();
    }
}
