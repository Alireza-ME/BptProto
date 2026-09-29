<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every users.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Users extends Group
{

    /**
     * users.getFullUser#b60f5918 = users.UserFull.
     */
    public function getFullUser(mixed $id): mixed
    {
        $b = Builder::ctor(0xB60F5918);
        $b->rawBlob($this->peers()->resolveUser($id));
        return Deserializer::parse($this->client->rpc($b->build()), 'users.UserFull');
    }

    /**
     * users.getRequirementsToContact#d89a83a3 = Vector<RequirementToContact>.
     */
    public function getRequirementsToContact(array $id): mixed
    {
        $b = Builder::ctor(0xD89A83A3);
        $b->vector(array_map(fn($x) => $this->peers()->resolveUser($x), $id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<RequirementToContact>');
    }

    /**
     * users.getSavedMusic#788d7fe3 = users.SavedMusic.
     */
    public function getSavedMusic(mixed $id, int $offset, int $limit, int $hash): mixed
    {
        $b = Builder::ctor(0x788D7FE3);
        $b->rawBlob($this->peers()->resolveUser($id));
        $b->int((int)$offset);
        $b->int((int)$limit);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'users.SavedMusic');
    }

    /**
     * users.getSavedMusicByID#7573a4e9 = users.SavedMusic.
     */
    public function getSavedMusicByID(mixed $id, array $documents): mixed
    {
        $b = Builder::ctor(0x7573A4E9);
        $b->rawBlob($this->peers()->resolveUser($id));
        $b->vector($documents);
        return Deserializer::parse($this->client->rpc($b->build()), 'users.SavedMusic');
    }

    /**
     * users.getUsers#d91a548 = Vector<User>.
     */
    public function getUsers(array $id): mixed
    {
        $b = Builder::ctor(0xD91A548);
        $b->vector(array_map(fn($x) => $this->peers()->resolveUser($x), $id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<User>');
    }

    /**
     * users.setSecureValueErrors#90c894b5 = Bool.
     */
    public function setSecureValueErrors(mixed $id, array $errors): mixed
    {
        $b = Builder::ctor(0x90C894B5);
        $b->rawBlob($this->peers()->resolveUser($id));
        $b->vector($errors);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * users.suggestBirthday#fc533372 = Updates.
     */
    public function suggestBirthday(mixed $id, string $birthday): mixed
    {
        $b = Builder::ctor(0xFC533372);
        $b->rawBlob($this->peers()->resolveUser($id));
        $b->rawBlob($birthday);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }
}
