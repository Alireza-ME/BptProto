<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every upload.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Upload extends Group
{

    /**
     * upload.getCdnFile#395f69da = upload.CdnFile.
     */
    public function getCdnFile(string $file_token, int $offset, int $limit): mixed
    {
        $b = Builder::ctor(0x395F69DA);
        $b->string((string)$file_token);
        $b->long((int)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'upload.CdnFile');
    }

    /**
     * upload.getCdnFileHashes#91dc3f31 = Vector<FileHash>.
     */
    public function getCdnFileHashes(string $file_token, int $offset): mixed
    {
        $b = Builder::ctor(0x91DC3F31);
        $b->string((string)$file_token);
        $b->long((int)$offset);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<FileHash>');
    }

    /**
     * upload.getFile#be5335be = upload.File.
     */
    public function getFile(string $location, int $offset, int $limit, bool $precise = false, bool $cdn_supported = false): mixed
    {
        $flags = 0;
        if ($precise) { $flags |= (1 << 0); }
        if ($cdn_supported) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xBE5335BE);
        $b->int($flags);
        $b->rawBlob($location);
        $b->long((int)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'upload.File');
    }

    /**
     * upload.getFileHashes#9156982a = Vector<FileHash>.
     */
    public function getFileHashes(string $location, int $offset): mixed
    {
        $b = Builder::ctor(0x9156982A);
        $b->rawBlob($location);
        $b->long((int)$offset);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<FileHash>');
    }

    /**
     * upload.getWebFile#24e6818d = upload.WebFile.
     */
    public function getWebFile(string $location, int $offset, int $limit): mixed
    {
        $b = Builder::ctor(0x24E6818D);
        $b->rawBlob($location);
        $b->int((int)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'upload.WebFile');
    }

    /**
     * upload.reuploadCdnFile#9b2754a8 = Vector<FileHash>.
     */
    public function reuploadCdnFile(string $file_token, string $request_token): mixed
    {
        $b = Builder::ctor(0x9B2754A8);
        $b->string((string)$file_token);
        $b->string((string)$request_token);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<FileHash>');
    }

    /**
     * upload.saveBigFilePart#de7b673d = Bool.
     */
    public function saveBigFilePart(int $file_id, int $file_part, int $file_total_parts, string $bytes): mixed
    {
        $b = Builder::ctor(0xDE7B673D);
        $b->long((int)$file_id);
        $b->int((int)$file_part);
        $b->int((int)$file_total_parts);
        $b->string((string)$bytes);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * upload.saveFilePart#b304a621 = Bool.
     */
    public function saveFilePart(int $file_id, int $file_part, string $bytes): mixed
    {
        $b = Builder::ctor(0xB304A621);
        $b->long((int)$file_id);
        $b->int((int)$file_part);
        $b->string((string)$bytes);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }
}
