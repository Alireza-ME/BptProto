<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every chatlists.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Chatlists extends Group
{

    /**
     * chatlists.checkChatlistInvite#41c10fff = chatlists.ChatlistInvite.
     */
    public function checkChatlistInvite(string $slug): mixed
    {
        $b = Builder::ctor(0x41C10FFF);
        $b->string((string)$slug);
        return Deserializer::parse($this->client->rpc($b->build()), 'chatlists.ChatlistInvite');
    }

    /**
     * chatlists.deleteExportedInvite#719c5c5e = Bool.
     */
    public function deleteExportedInvite(string $chatlist, string $slug): mixed
    {
        $b = Builder::ctor(0x719C5C5E);
        $b->rawBlob($chatlist);
        $b->string((string)$slug);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * chatlists.editExportedInvite#653db63d = ExportedChatlistInvite.
     */
    public function editExportedInvite(string $chatlist, string $slug, ?string $title = null, ?array $peers = null): mixed
    {
        $flags = 0;
        if ($title !== null) { $flags |= (1 << 1); }
        if ($peers !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x653DB63D);
        $b->int($flags);
        $b->rawBlob($chatlist);
        $b->string((string)$slug);
        if ($title !== null) { $b->string((string)$title); }
        if ($peers !== null) { $b->vector(array_map(fn($x) => $this->peerBlob($x), $peers)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'ExportedChatlistInvite');
    }

    /**
     * chatlists.exportChatlistInvite#8472478e = chatlists.ExportedChatlistInvite.
     */
    public function exportChatlistInvite(string $chatlist, string $title, array $peers): mixed
    {
        $b = Builder::ctor(0x8472478E);
        $b->rawBlob($chatlist);
        $b->string((string)$title);
        $b->vector(array_map(fn($x) => $this->peerBlob($x), $peers));
        return Deserializer::parse($this->client->rpc($b->build()), 'chatlists.ExportedChatlistInvite');
    }

    /**
     * chatlists.getChatlistUpdates#89419521 = chatlists.ChatlistUpdates.
     */
    public function getChatlistUpdates(string $chatlist): mixed
    {
        $b = Builder::ctor(0x89419521);
        $b->rawBlob($chatlist);
        return Deserializer::parse($this->client->rpc($b->build()), 'chatlists.ChatlistUpdates');
    }

    /**
     * chatlists.getExportedInvites#ce03da83 = chatlists.ExportedInvites.
     */
    public function getExportedInvites(string $chatlist): mixed
    {
        $b = Builder::ctor(0xCE03DA83);
        $b->rawBlob($chatlist);
        return Deserializer::parse($this->client->rpc($b->build()), 'chatlists.ExportedInvites');
    }

    /**
     * chatlists.getLeaveChatlistSuggestions#fdbcd714 = Vector<Peer>.
     */
    public function getLeaveChatlistSuggestions(string $chatlist): mixed
    {
        $b = Builder::ctor(0xFDBCD714);
        $b->rawBlob($chatlist);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<Peer>');
    }

    /**
     * chatlists.hideChatlistUpdates#66e486fb = Bool.
     */
    public function hideChatlistUpdates(string $chatlist): mixed
    {
        $b = Builder::ctor(0x66E486FB);
        $b->rawBlob($chatlist);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * chatlists.joinChatlistInvite#a6b1e39a = Updates.
     */
    public function joinChatlistInvite(string $slug, array $peers): mixed
    {
        $b = Builder::ctor(0xA6B1E39A);
        $b->string((string)$slug);
        $b->vector(array_map(fn($x) => $this->peerBlob($x), $peers));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * chatlists.joinChatlistUpdates#e089f8f5 = Updates.
     */
    public function joinChatlistUpdates(string $chatlist, array $peers): mixed
    {
        $b = Builder::ctor(0xE089F8F5);
        $b->rawBlob($chatlist);
        $b->vector(array_map(fn($x) => $this->peerBlob($x), $peers));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * chatlists.leaveChatlist#74fae13a = Updates.
     */
    public function leaveChatlist(string $chatlist, array $peers): mixed
    {
        $b = Builder::ctor(0x74FAE13A);
        $b->rawBlob($chatlist);
        $b->vector(array_map(fn($x) => $this->peerBlob($x), $peers));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }
}
