<?php

declare(strict_types=1);

namespace Bpt\TL;

/**
 * Constructor ids accepted by the tolerant parsers.
 *
 * Telegram changes some constructor ids across layers (e.g. user#020b1422
 * on layer 204 vs user#31774388 on current layers). The default layer
 * (see Config::DEFAULT_LAYER) yields the NEW ids; the OLD ones are kept
 * so cached data and pinned old layers still parse.
 */
final class Ctors
{
    /** @var int[] */
    public const USER = [0x020B1422, 0x31774388];
    public const USER_EMPTY = 0xD3BC4B7A;

    /** @var int[] */
    public const MESSAGE = [0xEABCDD4D, 0x95EF6F2B];
    public const MESSAGE_EMPTY = 0x90A6CA84;
    public const MESSAGE_SERVICE = 0x7A800E0A;

    /** @var int[] */
    public const DIALOG = [0xD58A08C6, 0xFC89F7F3];

    /** @var int[] messages.Messages containers */
    public const MESSAGES = [0x3A54685E, 0x1D73E7EA];
    /** @var int[] slice variants (flags,count,...) */
    public const MESSAGES_SLICE = [0x5F206716];
    /** @var int[] channel variants (flags,pts,count,...) */
    public const MESSAGES_CHANNEL = [0xC776BA4E];
    public const MESSAGES_NOT_MODIFIED = 0x74535F21;

    /** @var int[] messages.Dialogs containers */
    public const DIALOGS = [0x15BA6C40];
    public const DIALOGS_SLICE = 0x71E094F3;
    public const DIALOGS_NOT_MODIFIED = 0xF0E3E596;

    public const UPDATES = 0x74AE4240;
    public const UPDATE_SHORT_SENT = 0x9015E101;
    public const UPDATE_MESSAGE_ID = 0x4E90BFD6;

    public const CONTACTS = 0xEAE87E42;
    public const CONTACTS_NOT_MODIFIED = 0xB74BA9D2;
    public const CONTACT = 0x145ADE0B;
    public const IMPORTED_CONTACTS = 0x77D01C3B;
    public const FOUND = 0xB3134D9D;
    public const RESOLVED_PEER = 0x7F077AD9;

    public const PEER_USER = 0x59511722;
    public const PEER_CHAT = 0x36C6019A;
    public const PEER_CHANNEL = 0xA2A5371E;

    public const CHANNEL = 0x1C32B11C;
    public const AFFECTED_HISTORY = 0xB45C69D1;
    public const AFFECTED_MESSAGES = 0x84D19185;

    public static function isUser(int $ctor): bool
    {
        return in_array($ctor, self::USER, true);
    }

    public static function isMessage(int $ctor): bool
    {
        return in_array($ctor, self::MESSAGE, true);
    }

    public static function isDialog(int $ctor): bool
    {
        return in_array($ctor, self::DIALOG, true);
    }
}
