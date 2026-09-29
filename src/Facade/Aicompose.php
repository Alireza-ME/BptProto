<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every aicompose.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Aicompose extends Group
{

    /**
     * aicompose.createTone#4aa83913 = AiComposeTone.
     */
    public function createTone(int $emoji_id, string $title, string $prompt, bool $display_author = false): mixed
    {
        $flags = 0;
        if ($display_author) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x4AA83913);
        $b->int($flags);
        $b->long((int)$emoji_id);
        $b->string((string)$title);
        $b->string((string)$prompt);
        return Deserializer::parse($this->client->rpc($b->build()), 'AiComposeTone');
    }

    /**
     * aicompose.deleteTone#dd39316a = Bool.
     */
    public function deleteTone(string $tone): mixed
    {
        $b = Builder::ctor(0xDD39316A);
        $b->rawBlob($tone);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * aicompose.getTone#b2e8ba03 = aicompose.Tones.
     */
    public function getTone(string $tone): mixed
    {
        $b = Builder::ctor(0xB2E8BA03);
        $b->rawBlob($tone);
        return Deserializer::parse($this->client->rpc($b->build()), 'aicompose.Tones');
    }

    /**
     * aicompose.getToneExample#d1b4ab14 = AiComposeToneExample.
     */
    public function getToneExample(string $tone, int $num): mixed
    {
        $b = Builder::ctor(0xD1B4AB14);
        $b->rawBlob($tone);
        $b->int((int)$num);
        return Deserializer::parse($this->client->rpc($b->build()), 'AiComposeToneExample');
    }

    /**
     * aicompose.getTones#abd59201 = aicompose.Tones.
     */
    public function getTones(int $hash): mixed
    {
        $b = Builder::ctor(0xABD59201);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'aicompose.Tones');
    }

    /**
     * aicompose.saveTone#1782cbb1 = Bool.
     */
    public function saveTone(string $tone, bool $unsave): mixed
    {
        $b = Builder::ctor(0x1782CBB1);
        $b->rawBlob($tone);
        $b->bool((bool)$unsave);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * aicompose.updateTone#903bcf59 = AiComposeTone.
     */
    public function updateTone(string $tone, ?bool $display_author = null, ?int $emoji_id = null, ?string $title = null, ?string $prompt = null): mixed
    {
        $flags = 0;
        if ($display_author !== null) { $flags |= (1 << 0); }
        if ($emoji_id !== null) { $flags |= (1 << 1); }
        if ($title !== null) { $flags |= (1 << 2); }
        if ($prompt !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x903BCF59);
        $b->int($flags);
        $b->rawBlob($tone);
        if ($display_author !== null) { $b->bool((bool)$display_author); }
        if ($emoji_id !== null) { $b->long((int)$emoji_id); }
        if ($title !== null) { $b->string((string)$title); }
        if ($prompt !== null) { $b->string((string)$prompt); }
        return Deserializer::parse($this->client->rpc($b->build()), 'AiComposeTone');
    }
}
