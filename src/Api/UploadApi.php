<?php

declare(strict_types=1);

namespace Bpt\Api;

use Bpt\Session\EncryptedSession;
use Bpt\TL\Builder;
use Bpt\TL\Reader;

/**
 * Handwritten upload.* methods (files <10MB via saveFilePart).
 *
 * @see https://core.telegram.org/api/files
 */
final class UploadApi
{
    public function __construct(
        private readonly EncryptedSession $session,
        private readonly int $apiId,
        private readonly int $layer,
    ) {
    }

    /** upload.saveFilePart#b304a621 = Bool. */
    public function saveFilePart(int $fileId, int $part, string $bytes): bool
    {
        $body = Builder::ctor(0xB304A621)->long($fileId)->int($part)->bytes($bytes)->build();
        // saveFilePart is a bare call (no initConnection needed, but harmless).
        $raw = $this->session->callWithLayer($body, $this->apiId, $this->layer);
        return Reader::of($raw)->bool();
    }

    /**
     * upload.getFile#be5335be flags:# location offset:long limit:int = upload.File.
     *
     * @return array{type:int,mtime:int,bytes:string} type = storage.FileType ctor.
     */
    public function getFile(string $locationBlob, int $offset, int $limit): array
    {
        $body = Builder::ctor(0xBE5335BE)->int(0)->rawBlob($locationBlob)
            ->long($offset)->int($limit)->build();
        $raw = $this->session->callWithLayer($body, $this->apiId, $this->layer);
        $r = Reader::of($raw);
        $ctor = $r->ctor();
        if ($ctor === 0xF18CDA44) { // upload.fileCdnRedirect
            throw new \RuntimeException('getFile: CDN redirect not supported in v1');
        }
        if ($ctor !== 0x096A18D5) { // upload.file
            throw new \RuntimeException('getFile: unexpected 0x' . dechex($ctor));
        }
        $type = $r->ctor(); // storage.FileType
        $mtime = $r->int();
        $bytes = $r->bytes();
        return ['type' => $type, 'mtime' => $mtime, 'bytes' => $bytes];
    }
}
