<?php

declare(strict_types=1);

namespace Bpt\Api;

use Bpt\Session\EncryptedSession;
use Bpt\TL\Builder;

/**
 * Handwritten contacts.* methods.
 */
final class ContactsApi
{
    public function __construct(
        private readonly EncryptedSession $session,
        private readonly int $apiId,
        private readonly int $layer,
    ) {
    }

    /** contacts.getContacts#5dd69e12 hash:long = contacts.Contacts. */
    public function getContacts(int $hash = 0): string
    {
        $body = Builder::ctor(0x5DD69E12)->long($hash)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** contacts.getStatuses#c4a353ee = Vector<ContactStatus>. */
    public function getStatuses(): string
    {
        $body = Builder::ctor(0xC4A353EE)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** contacts.importContacts#2c800be5 contacts:Vector<InputContact> = contacts.ImportedContacts. */
    public function importContacts(array $inputContacts): string
    {
        $body = Builder::ctor(0x2C800BE5)->vector($inputContacts)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** contacts.search#11f812d8 q:string limit:int = contacts.Found. */
    public function search(string $q, int $limit = 10): string
    {
        $body = Builder::ctor(0x11F812D8)->string($q)->int($limit)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** contacts.resolveUsername#725afbbc flags:# username:string = contacts.ResolvedPeer. */
    public function resolveUsername(string $username): string
    {
        $body = Builder::ctor(0x725AFBBC)->int(0)->string($username)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }
}
