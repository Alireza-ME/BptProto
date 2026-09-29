<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every stickers.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Stickers extends Group
{

    /**
     * stickers.addStickerToSet#8653febe = messages.StickerSet.
     */
    public function addStickerToSet(string $stickerset, string $sticker): mixed
    {
        $b = Builder::ctor(0x8653FEBE);
        $b->rawBlob($stickerset);
        $b->rawBlob($sticker);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.StickerSet');
    }

    /**
     * stickers.changeSticker#f5537ebc = messages.StickerSet.
     */
    public function changeSticker(string $sticker, ?string $emoji = null, ?string $mask_coords = null, ?string $keywords = null): mixed
    {
        $flags = 0;
        if ($emoji !== null) { $flags |= (1 << 0); }
        if ($mask_coords !== null) { $flags |= (1 << 1); }
        if ($keywords !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xF5537EBC);
        $b->int($flags);
        $b->rawBlob($sticker);
        if ($emoji !== null) { $b->string((string)$emoji); }
        if ($mask_coords !== null) { $b->rawBlob($mask_coords); }
        if ($keywords !== null) { $b->string((string)$keywords); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.StickerSet');
    }

    /**
     * stickers.changeStickerPosition#ffb6d4ca = messages.StickerSet.
     */
    public function changeStickerPosition(string $sticker, int $position): mixed
    {
        $b = Builder::ctor(0xFFB6D4CA);
        $b->rawBlob($sticker);
        $b->int((int)$position);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.StickerSet');
    }

    /**
     * stickers.checkShortName#284b3639 = Bool.
     */
    public function checkShortName(string $short_name): mixed
    {
        $b = Builder::ctor(0x284B3639);
        $b->string((string)$short_name);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * stickers.createStickerSet#9021ab67 = messages.StickerSet.
     */
    public function createStickerSet(mixed $user_id, string $title, string $short_name, array $stickers, bool $masks = false, bool $emojis = false, bool $text_color = false, ?string $thumb = null, ?string $software = null): mixed
    {
        $flags = 0;
        if ($masks) { $flags |= (1 << 0); }
        if ($emojis) { $flags |= (1 << 5); }
        if ($text_color) { $flags |= (1 << 6); }
        if ($thumb !== null) { $flags |= (1 << 2); }
        if ($software !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x9021AB67);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->string((string)$title);
        $b->string((string)$short_name);
        if ($thumb !== null) { $b->rawBlob($thumb); }
        $b->vector($stickers);
        if ($software !== null) { $b->string((string)$software); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.StickerSet');
    }

    /**
     * stickers.deleteStickerSet#87704394 = Bool.
     */
    public function deleteStickerSet(string $stickerset): mixed
    {
        $b = Builder::ctor(0x87704394);
        $b->rawBlob($stickerset);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * stickers.removeStickerFromSet#f7760f51 = messages.StickerSet.
     */
    public function removeStickerFromSet(string $sticker): mixed
    {
        $b = Builder::ctor(0xF7760F51);
        $b->rawBlob($sticker);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.StickerSet');
    }

    /**
     * stickers.renameStickerSet#124b1c00 = messages.StickerSet.
     */
    public function renameStickerSet(string $stickerset, string $title): mixed
    {
        $b = Builder::ctor(0x124B1C00);
        $b->rawBlob($stickerset);
        $b->string((string)$title);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.StickerSet');
    }

    /**
     * stickers.replaceSticker#4696459a = messages.StickerSet.
     */
    public function replaceSticker(string $sticker, string $new_sticker): mixed
    {
        $b = Builder::ctor(0x4696459A);
        $b->rawBlob($sticker);
        $b->rawBlob($new_sticker);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.StickerSet');
    }

    /**
     * stickers.setStickerSetThumb#a76a5392 = messages.StickerSet.
     */
    public function setStickerSetThumb(string $stickerset, ?string $thumb = null, ?int $thumb_document_id = null): mixed
    {
        $flags = 0;
        if ($thumb !== null) { $flags |= (1 << 0); }
        if ($thumb_document_id !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xA76A5392);
        $b->int($flags);
        $b->rawBlob($stickerset);
        if ($thumb !== null) { $b->rawBlob($thumb); }
        if ($thumb_document_id !== null) { $b->long((int)$thumb_document_id); }
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.StickerSet');
    }

    /**
     * stickers.suggestShortName#4dafc503 = stickers.SuggestedShortName.
     */
    public function suggestShortName(string $title): mixed
    {
        $b = Builder::ctor(0x4DAFC503);
        $b->string((string)$title);
        return Deserializer::parse($this->client->rpc($b->build()), 'stickers.SuggestedShortName');
    }
}
