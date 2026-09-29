<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Peer;

/** Upload / download (<10MB via saveFilePart). → `$proto->files` */
final class Files extends Group
{
    /**
     * Upload a local file in 512KB chunks (saveFilePart path).
     *
     * @return array{id:int,parts:int,name:string,size:int}
     */
    public function uploadFile(string $path, int $chunk = 524288): array
    {
        if (!is_file($path)) {
            throw new \InvalidArgumentException("uploadFile: not found: $path");
        }
        $size = filesize($path);
        if ($size === false || $size <= 0) {
            throw new \InvalidArgumentException('uploadFile: empty file');
        }
        if ($size > 10 * 1024 * 1024) {
            throw new \RuntimeException('uploadFile: >10MB needs saveBigFilePart (use rpc() raw for now)');
        }
        $data = file_get_contents($path);
        if ($data === false) {
            throw new \RuntimeException("uploadFile: cannot read $path");
        }
        $fileId = random_int(1, PHP_INT_MAX);
        $parts = (int)ceil(strlen($data) / $chunk);
        for ($i = 0; $i < $parts; $i++) {
            $piece = substr($data, $i * $chunk, $chunk);
            $ok = $this->client->withRetry(fn() => $this->client->api()->upload()->saveFilePart($fileId, $i, $piece));
            if (!$ok) {
                throw new \RuntimeException("uploadFile: part $i rejected");
            }
        }
        return ['id' => $fileId, 'parts' => $parts, 'name' => basename($path), 'size' => $size];
    }

    /**
     * Download via upload.getFile loop into $destPath.
     *
     * @param string $locationBlob Packed InputFileLocation (see Peer::documentLocation()).
     * @return string $destPath (written file).
     */
    public function downloadFile(string $locationBlob, string $destPath, int $chunk = 524288, int $offset = 0): string
    {
        $fh = fopen($destPath, $offset > 0 ? 'ab' : 'wb');
        if ($fh === false) {
            throw new \RuntimeException("downloadFile: cannot open $destPath");
        }
        try {
            while (true) {
                $part = $this->client->withRetry(fn() => $this->client->api()->upload()->getFile($locationBlob, $offset, $chunk));
                $bytes = $part['bytes'] ?? '';
                if ($bytes === '') {
                    break;
                }
                fwrite($fh, $bytes);
                $offset += strlen($bytes);
                if (strlen($bytes) < $chunk) {
                    break;
                }
            }
        } finally {
            fclose($fh);
        }
        return $destPath;
    }

    /** Download a document by id+hash (file_reference empty works for most). */
    public function downloadDocument(int $docId, int $accessHash, string $destPath, string $fileRef = '', int $chunk = 524288): string
    {
        return $this->downloadFile(Peer::documentLocation($docId, $accessHash, $fileRef), $destPath, $chunk);
    }
}
