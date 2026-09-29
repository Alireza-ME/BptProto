<?php

declare(strict_types=1);

namespace Bpt\Api;

use Bpt\Session\EncryptedSession;
use Bpt\TL\Builder;

/**
 * Handwritten users.* methods.
 */
final class UsersApi
{
    public function __construct(
        private readonly EncryptedSession $session,
        private readonly int $apiId,
        private readonly int $layer,
    ) {
    }

    /** users.getUsers#d91a548 id:Vector<InputUser> = Vector<User>. */
    public function getUsers(array $inputUsers): string
    {
        $body = Builder::ctor(0xD91A548)->vector($inputUsers)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** users.getFullUser#b60f5918 id:InputUser = users.UserFull. */
    public function getFullUser(string $inputUser): string
    {
        $body = Builder::ctor(0xB60F5918)->rawBlob($inputUser)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }
}
