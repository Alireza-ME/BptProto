<?php

declare(strict_types=1);

namespace Bpt\TL;

use Bpt\Codec\TlCodec;

/**
 * Factories for already-packed InputPeer / InputUser / InputChannel blobs.
 *
 * Returned strings are complete TL objects (ctor + fields) ready to be
 * appended with {@see Builder::rawBlob()}.
 */
final class Peer
{
    public static function self(): string
    {
        return TlCodec::packInt(0x7DA07EC9); // inputPeerSelf
    }

    public static function empty(): string
    {
        return TlCodec::packInt(0x7F3B18EA); // inputPeerEmpty
    }

    public static function user(int $userId, int $accessHash): string
    {
        return TlCodec::packInt(0xDDE8A54C) // inputPeerUser
            . TlCodec::packLong($userId) . TlCodec::packLong($accessHash);
    }

    public static function chat(int $chatId): string
    {
        return TlCodec::packInt(0x35A95CB9) // inputPeerChat
            . TlCodec::packLong($chatId);
    }

    public static function channel(int $channelId, int $accessHash): string
    {
        return TlCodec::packInt(0x27BCBBFC) // inputPeerChannel
            . TlCodec::packLong($channelId) . TlCodec::packLong($accessHash);
    }

    public static function inputUserSelf(): string
    {
        return TlCodec::packInt(0xF7C1B13F); // inputUserSelf
    }

    public static function inputUserEmpty(): string
    {
        return TlCodec::packInt(0xB98886CF); // inputUserEmpty
    }

    public static function inputUser(int $userId, int $accessHash): string
    {
        return TlCodec::packInt(0xF21158C6) // inputUser
            . TlCodec::packLong($userId) . TlCodec::packLong($accessHash);
    }

    public static function inputChannel(int $channelId, int $accessHash): string
    {
        return TlCodec::packInt(0xF35AEC28) // inputChannel
            . TlCodec::packLong($channelId) . TlCodec::packLong($accessHash);
    }

    /** inputDialogPeer#fcaafeb7 peer:InputPeer (wraps any packed InputPeer). */
    public static function inputDialogPeer(string $inputPeer): string
    {
        return TlCodec::packInt(0xFCAAFEB7) . $inputPeer;
    }

    public static function inputPhoneContact(int $clientId, string $phone, string $firstName, string $lastName = ''): string
    {
        return TlCodec::packInt(0x6A1DC4BE) // inputPhoneContact
            . TlCodec::packLong($clientId)
            . TlCodec::encodeBytes($phone)
            . TlCodec::encodeBytes($firstName)
            . TlCodec::encodeBytes($lastName);
    }

    // ----------------------------------------------------------------- files
    // Minimal InputFile / InputMedia / InputFileLocation factories for the
    // <10MB upload path (upload.saveFilePart). Bigger files need
    // upload.saveBigFilePart (not yet wrapped — use rpc() raw).

    /** inputFile#f52ff27f. $parts = number of saveFilePart chunks. */
    public static function inputFile(int $id, int $parts, string $name): string
    {
        return TlCodec::packInt(0xF52FF27F)
            . TlCodec::packLong($id) . TlCodec::packInt($parts)
            . TlCodec::encodeBytes($name) . TlCodec::encodeBytes('');
    }

    /** documentAttributeFilename#15590068. */
    public static function docAttrFilename(string $name): string
    {
        return TlCodec::packInt(0x15590068) . TlCodec::encodeBytes($name);
    }

    /** inputMediaUploadedPhoto#7d8375da flags=0 subset. */
    public static function inputMediaPhoto(string $inputFile): string
    {
        return TlCodec::packInt(0x7D8375DA) . TlCodec::packInt(0) . $inputFile;
    }

    /** inputMediaUploadedDocument#037c9330 flags=0 subset (generic file). */
    public static function inputMediaDocument(string $inputFile, string $mime, string $fileName): string
    {
        $attrs = TlCodec::packInt(0x1CB5C415) . TlCodec::packInt(1) . self::docAttrFilename($fileName);
        return TlCodec::packInt(0x037C9330) . TlCodec::packInt(0)
            . $inputFile . TlCodec::encodeBytes($mime) . $attrs;
    }

    /** inputDocumentFileLocation#bad07584 (for upload.getFile). */
    public static function documentLocation(int $docId, int $accessHash, string $fileRef = '', string $thumb = ''): string
    {
        return TlCodec::packInt(0xBAD07584)
            . TlCodec::packLong($docId) . TlCodec::packLong($accessHash)
            . TlCodec::encodeBytes($fileRef) . TlCodec::encodeBytes($thumb);
    }

    /**
     * Normalize user-facing peer shortcuts.
     *
     * 'me' | 'self' | null | 0  →  is-self marker (caller maps to self()).
     */
    public static function isSelf(mixed $peer): bool
    {
        if ($peer === null || $peer === 0 || $peer === '0') {
            return true;
        }
        if (is_string($peer)) {
            $p = strtolower(trim($peer));
            return $p === '' || $p === 'me' || $p === 'self' || $p === 'saved';
        }
        return false;
    }

    public static function isUsername(mixed $peer): bool
    {
        if (!is_string($peer)) {
            return false;
        }
        $p = trim($peer);
        return $p !== '' && ($p[0] === '@' || preg_match('/^[a-zA-Z][a-zA-Z0-9_]{3,31}$/', $p) === 1);
    }

    public static function normUsername(string $u): string
    {
        return ltrim(trim($u), '@');
    }
}
