<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every ephemeral.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Ephemeral extends Group
{

    /**
     * ephemeral.deleteAllWelcomeMessages#734f9721 = Bool.
     */
    public function deleteAllWelcomeMessages(mixed $peer): mixed
    {
        $b = Builder::ctor(0x734F9721);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * ephemeral.deleteMessage#92f6e797 = Bool.
     */
    public function deleteMessage(mixed $receiver_id, int $id, mixed $peer = null): mixed
    {
        $flags = 0;
        if ($peer !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x92F6E797);
        $b->int($flags);
        if ($peer !== null) { $b->rawBlob($this->peerBlob($peer)); }
        $b->rawBlob($this->peers()->resolveUser($receiver_id));
        $b->int((int)$id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * ephemeral.deleteWelcomeMessage#e882a9e1 = Bool.
     */
    public function deleteWelcomeMessage(mixed $peer, int $id): mixed
    {
        $b = Builder::ctor(0xE882A9E1);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * ephemeral.editMessage#cf9c725b = Updates.
     */
    public function editMessage(mixed $receiver_id, int $id, bool $invert_media = false, bool $welcome = false, mixed $peer = null, ?string $message = null, ?string $media = null, ?array $entities = null, ?string $reply_markup = null, ?string $rich_message = null): mixed
    {
        $flags = 0;
        if ($invert_media) { $flags |= (1 << 5); }
        if ($welcome) { $flags |= (1 << 6); }
        if ($peer !== null) { $flags |= (1 << 7); }
        if ($message !== null) { $flags |= (1 << 0); }
        if ($media !== null) { $flags |= (1 << 3); }
        if ($entities !== null) { $flags |= (1 << 1); }
        if ($reply_markup !== null) { $flags |= (1 << 2); }
        if ($rich_message !== null) { $flags |= (1 << 4); }
        $b = Builder::ctor(0xCF9C725B);
        $b->int($flags);
        if ($peer !== null) { $b->rawBlob($this->peerBlob($peer)); }
        $b->rawBlob($this->peers()->resolveUser($receiver_id));
        $b->int((int)$id);
        if ($message !== null) { $b->string((string)$message); }
        if ($media !== null) { $b->rawBlob($media); }
        if ($entities !== null) { $b->vector($entities); }
        if ($reply_markup !== null) { $b->rawBlob($reply_markup); }
        if ($rich_message !== null) { $b->rawBlob($rich_message); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * ephemeral.getCallbackAnswer#3fa464c8 = messages.BotCallbackAnswer.
     */
    public function getCallbackAnswer(mixed $peer, int $id, ?string $data = null): mixed
    {
        $flags = 0;
        if ($data !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x3FA464C8);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        if ($data !== null) { $b->string((string)$data); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.BotCallbackAnswer');
    }

    /**
     * ephemeral.getWelcomeMessages#db9ac18d = ephemeral.WelcomeMessages.
     */
    public function getWelcomeMessages(mixed $peer, int $hash): mixed
    {
        $b = Builder::ctor(0xDB9AC18D);
        $b->rawBlob($this->peerBlob($peer));
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'ephemeral.WelcomeMessages');
    }

    /**
     * ephemeral.reportMessage#8704f2bf = ReportResult.
     */
    public function reportMessage(mixed $peer, int $id, string $option, string $message): mixed
    {
        $b = Builder::ctor(0x8704F2BF);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        $b->string((string)$option);
        $b->string((string)$message);
        return Deserializer::parse($this->client->rpc($b->build()), 'ReportResult');
    }

    /**
     * ephemeral.sendMessage#ba8d5f35 = Updates.
     */
    public function sendMessage(mixed $receiver_id, string $message, int $random_id, bool $invert_media = false, bool $welcome = false, bool $anchor = false, bool $noforwards = false, mixed $peer = null, ?int $query_id = null, ?array $entities = null, ?string $media = null, ?string $reply_markup = null, ?string $rich_message = null, ?string $reply_to = null): mixed
    {
        $flags = 0;
        if ($invert_media) { $flags |= (1 << 6); }
        if ($welcome) { $flags |= (1 << 7); }
        if ($anchor) { $flags |= (1 << 9); }
        if ($noforwards) { $flags |= (1 << 10); }
        if ($peer !== null) { $flags |= (1 << 8); }
        if ($query_id !== null) { $flags |= (1 << 0); }
        if ($entities !== null) { $flags |= (1 << 1); }
        if ($media !== null) { $flags |= (1 << 2); }
        if ($reply_markup !== null) { $flags |= (1 << 3); }
        if ($rich_message !== null) { $flags |= (1 << 4); }
        if ($reply_to !== null) { $flags |= (1 << 5); }
        $b = Builder::ctor(0xBA8D5F35);
        $b->int($flags);
        if ($peer !== null) { $b->rawBlob($this->peerBlob($peer)); }
        $b->rawBlob($this->peers()->resolveUser($receiver_id));
        if ($query_id !== null) { $b->long((int)$query_id); }
        $b->string((string)$message);
        if ($entities !== null) { $b->vector($entities); }
        if ($media !== null) { $b->rawBlob($media); }
        if ($reply_markup !== null) { $b->rawBlob($reply_markup); }
        if ($rich_message !== null) { $b->rawBlob($rich_message); }
        $b->long((int)$random_id);
        if ($reply_to !== null) { $b->rawBlob($reply_to); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }
}
