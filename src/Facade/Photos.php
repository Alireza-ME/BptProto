<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every photos.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Photos extends Group
{

    /**
     * photos.deletePhotos#87cf7f2f = Vector<long>.
     */
    public function deletePhotos(array $id): mixed
    {
        $b = Builder::ctor(0x87CF7F2F);
        $b->vector($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<long>');
    }

    /**
     * photos.getUserPhotos#91cd32a8 = photos.Photos.
     */
    public function getUserPhotos(mixed $user_id, int $offset, int $max_id, int $limit): mixed
    {
        $b = Builder::ctor(0x91CD32A8);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->int((int)$offset);
        $b->long((int)$max_id);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'photos.Photos');
    }

    /**
     * photos.updateProfilePhoto#9e82039 = photos.Photo.
     */
    public function updateProfilePhoto(string $id, bool $fallback = false, mixed $bot = null): mixed
    {
        $flags = 0;
        if ($fallback) { $flags |= (1 << 0); }
        if ($bot !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x9E82039);
        $b->int($flags);
        if ($bot !== null) { $b->rawBlob($this->peers()->resolveUser($bot)); }
        $b->rawBlob($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'photos.Photo');
    }

    /**
     * photos.uploadContactProfilePhoto#e14c4a71 = photos.Photo.
     */
    public function uploadContactProfilePhoto(mixed $user_id, bool $suggest = false, bool $save = false, ?string $file = null, ?string $video = null, ?float $video_start_ts = null, ?string $video_emoji_markup = null): mixed
    {
        $flags = 0;
        if ($suggest) { $flags |= (1 << 3); }
        if ($save) { $flags |= (1 << 4); }
        if ($file !== null) { $flags |= (1 << 0); }
        if ($video !== null) { $flags |= (1 << 1); }
        if ($video_start_ts !== null) { $flags |= (1 << 2); }
        if ($video_emoji_markup !== null) { $flags |= (1 << 5); }
        $b = Builder::ctor(0xE14C4A71);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        if ($file !== null) { $b->rawBlob($file); }
        if ($video !== null) { $b->rawBlob($video); }
        if ($video_start_ts !== null) { $b->double((float)$video_start_ts); }
        if ($video_emoji_markup !== null) { $b->rawBlob($video_emoji_markup); }
        return Deserializer::parse($this->client->rpc($b->build()), 'photos.Photo');
    }

    /**
     * photos.uploadProfilePhoto#388a3b5 = photos.Photo.
     */
    public function uploadProfilePhoto(bool $fallback = false, mixed $bot = null, ?string $file = null, ?string $video = null, ?float $video_start_ts = null, ?string $video_emoji_markup = null): mixed
    {
        $flags = 0;
        if ($fallback) { $flags |= (1 << 3); }
        if ($bot !== null) { $flags |= (1 << 5); }
        if ($file !== null) { $flags |= (1 << 0); }
        if ($video !== null) { $flags |= (1 << 1); }
        if ($video_start_ts !== null) { $flags |= (1 << 2); }
        if ($video_emoji_markup !== null) { $flags |= (1 << 4); }
        $b = Builder::ctor(0x388A3B5);
        $b->int($flags);
        if ($bot !== null) { $b->rawBlob($this->peers()->resolveUser($bot)); }
        if ($file !== null) { $b->rawBlob($file); }
        if ($video !== null) { $b->rawBlob($video); }
        if ($video_start_ts !== null) { $b->double((float)$video_start_ts); }
        if ($video_emoji_markup !== null) { $b->rawBlob($video_emoji_markup); }
        return Deserializer::parse($this->client->rpc($b->build()), 'photos.Photo');
    }
}
