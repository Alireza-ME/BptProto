<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every contacts.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Contacts extends Group
{

    /**
     * contacts.acceptContact#f831a20f = Updates.
     */
    public function acceptContact(mixed $id): mixed
    {
        $b = Builder::ctor(0xF831A20F);
        $b->rawBlob($this->peers()->resolveUser($id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * contacts.addContact#d9ba2e54 = Updates.
     */
    public function addContact(mixed $id, string $first_name, string $last_name, string $phone, bool $add_phone_privacy_exception = false, ?string $note = null): mixed
    {
        $flags = 0;
        if ($add_phone_privacy_exception) { $flags |= (1 << 0); }
        if ($note !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xD9BA2E54);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveUser($id));
        $b->string((string)$first_name);
        $b->string((string)$last_name);
        $b->string((string)$phone);
        if ($note !== null) { $b->rawBlob($note); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * contacts.block#2e2e8734 = Bool.
     */
    public function block(mixed $id, bool $my_stories_from = false): mixed
    {
        $flags = 0;
        if ($my_stories_from) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x2E2E8734);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * contacts.blockFromReplies#29a8962c = Updates.
     */
    public function blockFromReplies(int $msg_id, bool $delete_message = false, bool $delete_history = false, bool $report_spam = false): mixed
    {
        $flags = 0;
        if ($delete_message) { $flags |= (1 << 0); }
        if ($delete_history) { $flags |= (1 << 1); }
        if ($report_spam) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x29A8962C);
        $b->int($flags);
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * contacts.deleteByPhones#1013fd9e = Bool.
     */
    public function deleteByPhones(array $phones): mixed
    {
        $b = Builder::ctor(0x1013FD9E);
        $b->vectorString($phones);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * contacts.deleteContacts#96a0e00 = Updates.
     */
    public function deleteContacts(array $id): mixed
    {
        $b = Builder::ctor(0x96A0E00);
        $b->vector(array_map(fn($x) => $this->peers()->resolveUser($x), $id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * contacts.editCloseFriends#ba6705f0 = Bool.
     */
    public function editCloseFriends(array $id): mixed
    {
        $b = Builder::ctor(0xBA6705F0);
        $b->vectorLong($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * contacts.exportContactToken#f8654027 = ExportedContactToken.
     */
    public function exportContactToken(): mixed
    {
        $b = Builder::ctor(0xF8654027);
        return Deserializer::parse($this->client->rpc($b->build()), 'ExportedContactToken');
    }

    /**
     * contacts.getBirthdays#daeda864 = contacts.ContactBirthdays.
     */
    public function getBirthdays(): mixed
    {
        $b = Builder::ctor(0xDAEDA864);
        return Deserializer::parse($this->client->rpc($b->build()), 'contacts.ContactBirthdays');
    }

    /**
     * contacts.getBlocked#9a868f80 = contacts.Blocked.
     */
    public function getBlocked(int $offset, int $limit, bool $my_stories_from = false): mixed
    {
        $flags = 0;
        if ($my_stories_from) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x9A868F80);
        $b->int($flags);
        $b->int((int)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'contacts.Blocked');
    }

    /**
     * contacts.getContactIDs#7adc669d = Vector<int>.
     */
    public function getContactIDs(int $hash): mixed
    {
        $b = Builder::ctor(0x7ADC669D);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<int>');
    }

    /**
     * contacts.getContacts#5dd69e12 = contacts.Contacts.
     */
    public function getContacts(int $hash): mixed
    {
        $b = Builder::ctor(0x5DD69E12);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'contacts.Contacts');
    }

    /**
     * contacts.getLocated#d348bc44 = Updates.
     */
    public function getLocated(string $geo_point, bool $background = false, ?int $self_expires = null): mixed
    {
        $flags = 0;
        if ($background) { $flags |= (1 << 1); }
        if ($self_expires !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xD348BC44);
        $b->int($flags);
        $b->rawBlob($geo_point);
        if ($self_expires !== null) { $b->int((int)$self_expires); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * contacts.getSaved#82f1e39f = Vector<SavedContact>.
     */
    public function getSaved(): mixed
    {
        $b = Builder::ctor(0x82F1E39F);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<SavedContact>');
    }

    /**
     * contacts.getSponsoredPeers#b6c8c393 = contacts.SponsoredPeers.
     */
    public function getSponsoredPeers(string $q): mixed
    {
        $b = Builder::ctor(0xB6C8C393);
        $b->string((string)$q);
        return Deserializer::parse($this->client->rpc($b->build()), 'contacts.SponsoredPeers');
    }

    /**
     * contacts.getStatuses#c4a353ee = Vector<ContactStatus>.
     */
    public function getStatuses(): mixed
    {
        $b = Builder::ctor(0xC4A353EE);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<ContactStatus>');
    }

    /**
     * contacts.getTopPeers#973478b6 = contacts.TopPeers.
     */
    public function getTopPeers(int $offset, int $limit, int $hash, bool $correspondents = false, bool $bots_pm = false, bool $bots_inline = false, bool $phone_calls = false, bool $forward_users = false, bool $forward_chats = false, bool $groups = false, bool $channels = false, bool $bots_app = false, bool $bots_guestchat = false): mixed
    {
        $flags = 0;
        if ($correspondents) { $flags |= (1 << 0); }
        if ($bots_pm) { $flags |= (1 << 1); }
        if ($bots_inline) { $flags |= (1 << 2); }
        if ($phone_calls) { $flags |= (1 << 3); }
        if ($forward_users) { $flags |= (1 << 4); }
        if ($forward_chats) { $flags |= (1 << 5); }
        if ($groups) { $flags |= (1 << 10); }
        if ($channels) { $flags |= (1 << 15); }
        if ($bots_app) { $flags |= (1 << 16); }
        if ($bots_guestchat) { $flags |= (1 << 17); }
        $b = Builder::ctor(0x973478B6);
        $b->int($flags);
        $b->int((int)$offset);
        $b->int((int)$limit);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'contacts.TopPeers');
    }

    /**
     * contacts.importContactToken#13005788 = User.
     */
    public function importContactToken(string $token): mixed
    {
        $b = Builder::ctor(0x13005788);
        $b->string((string)$token);
        return Deserializer::parse($this->client->rpc($b->build()), 'User');
    }

    /**
     * contacts.importContacts#2c800be5 = contacts.ImportedContacts.
     */
    public function importContacts(array $contacts): mixed
    {
        $b = Builder::ctor(0x2C800BE5);
        $b->vector($contacts);
        return Deserializer::parse($this->client->rpc($b->build()), 'contacts.ImportedContacts');
    }

    /**
     * contacts.resetSaved#879537f1 = Bool.
     */
    public function resetSaved(): mixed
    {
        $b = Builder::ctor(0x879537F1);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * contacts.resetTopPeerRating#1ae373ac = Bool.
     */
    public function resetTopPeerRating(string $category, mixed $peer): mixed
    {
        $b = Builder::ctor(0x1AE373AC);
        $b->rawBlob($category);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * contacts.resolvePhone#8af94344 = contacts.ResolvedPeer.
     */
    public function resolvePhone(string $phone): mixed
    {
        $b = Builder::ctor(0x8AF94344);
        $b->string((string)$phone);
        return Deserializer::parse($this->client->rpc($b->build()), 'contacts.ResolvedPeer');
    }

    /**
     * contacts.resolveUsername#725afbbc = contacts.ResolvedPeer.
     */
    public function resolveUsername(string $username, ?string $referer = null): mixed
    {
        $flags = 0;
        if ($referer !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x725AFBBC);
        $b->int($flags);
        $b->string((string)$username);
        if ($referer !== null) { $b->string((string)$referer); }
        return Deserializer::parse($this->client->rpc($b->build()), 'contacts.ResolvedPeer');
    }

    /**
     * contacts.search#5f58d0f = contacts.Found.
     */
    public function search(string $q, int $limit, bool $broadcasts = false, bool $bots = false): mixed
    {
        $flags = 0;
        if ($broadcasts) { $flags |= (1 << 0); }
        if ($bots) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x5F58D0F);
        $b->int($flags);
        $b->string((string)$q);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'contacts.Found');
    }

    /**
     * contacts.setBlocked#94c65c76 = Bool.
     */
    public function setBlocked(array $id, int $limit, bool $my_stories_from = false): mixed
    {
        $flags = 0;
        if ($my_stories_from) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x94C65C76);
        $b->int($flags);
        $b->vector(array_map(fn($x) => $this->peerBlob($x), $id));
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * contacts.toggleTopPeers#8514bdda = Bool.
     */
    public function toggleTopPeers(bool $enabled): mixed
    {
        $b = Builder::ctor(0x8514BDDA);
        $b->bool((bool)$enabled);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * contacts.unblock#b550d328 = Bool.
     */
    public function unblock(mixed $id, bool $my_stories_from = false): mixed
    {
        $flags = 0;
        if ($my_stories_from) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xB550D328);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * contacts.updateContactNote#139f63fb = Bool.
     */
    public function updateContactNote(mixed $id, string $note): mixed
    {
        $b = Builder::ctor(0x139F63FB);
        $b->rawBlob($this->peers()->resolveUser($id));
        $b->rawBlob($note);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }
}
