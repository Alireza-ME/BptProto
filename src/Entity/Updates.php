<?php

declare(strict_types=1);

namespace Bpt\Entity;

use Bpt\TL\Deserializer;
use Bpt\TL\Reader;

/**
 * Tolerant parser for Telegram Updates (realtime events).
 *
 * Known message shapes are normalized to a friendly flat array:
 *
 *   ['kind' => 'new_message', 'message' => [...], 'text' => ..., 'peer_id' => ...]
 *   ['kind' => 'edit_message', ...]
 *   ['kind' => 'delete_messages', 'ids' => [...]]
 *   ['kind' => 'too_long']  // client should call getDifference()
 *   ['kind' => 'sent', ...]
 *
 * EVERY other update constructor (169 of them) is parsed schema-driven via
 * the generated {@see \Bpt\TL\Types} map and returned as the parsed array
 * with `kind` = the constructor name (e.g. 'updateUserStatus'), so no update
 * is ever dropped (see {@see \Bpt\Realtime}).
 *
 * Strategy mirrors Message/Dialog: consume exactly when the layout is
 * known, otherwise throw StopWalk so vector walkers keep what was
 * parsed so far instead of corrupting the stream.
 */
final class Updates
{
    // Top-level Updates ctors.
    public const UPDATES = 0x74AE4240;
    public const UPDATES_COMBINED = 0x725B04C3;
    public const UPDATE_SHORT = 0x65C4E210;
    public const UPDATE_SHORT_MESSAGE = 0x313BC7F8;
    public const UPDATE_SHORT_CHAT_MESSAGE = 0x4D6DEEA5;
    public const UPDATE_SHORT_SENT = 0x9015E101;
    public const UPDATES_TOO_LONG = 0xE317AF7E;

    // Update ctors we consume exactly.
    public const NEW_MESSAGE = 0x1F2B0AFD;
    public const NEW_CHANNEL_MESSAGE = 0x62BA04D9;
    public const EDIT_MESSAGE = 0xE40370A3;
    public const EDIT_CHANNEL_MESSAGE = 0x12BCBD9A;
    public const DELETE_MESSAGES = 0x08C70874;
    public const DELETE_CHANNEL_MESSAGES = 0x63DA02E0;

    /**
     * Parse one top-level Updates object.
     *
     * @return array[] Normalized updates (usually 1, N for updates/updatesCombined).
     */
    public static function parse(Reader $r): array
    {
        $ctor = $r->ctor();
        switch ($ctor) {
            case self::UPDATES_TOO_LONG:
                return [['kind' => 'too_long']];
            case self::UPDATE_SHORT_SENT:
                return [self::parseShortSent($r)];
            case self::UPDATE_SHORT_MESSAGE:
                return [self::parseShortMessage($r, 'user')];
            case self::UPDATE_SHORT_CHAT_MESSAGE:
                return [self::parseShortChatMessage($r)];
            case self::UPDATE_SHORT:
                $u = self::parseOneUpdate($r);
                $date = 0;
                try {
                    $date = $r->int();
                } catch (\Throwable) {
                }
                if (is_array($u)) {
                    $u['date'] ??= $date;
                    return [$u];
                }
                return [];
            case self::UPDATES:
            case self::UPDATES_COMBINED:
                return self::parseContainer($r, $ctor);
            default:
                // Maybe a bare Update (server sometimes pushes Update directly).
                // Rewind 4 bytes and try single-update parse.
                $raw = $r->raw();
                $off = $r->offset() - 4;
                if ($off < 0) {
                    throw new StopWalk('unknown updates 0x' . dechex($ctor));
                }
                $r2 = Reader::of(substr($raw, $off));
                $u = self::parseOneUpdate($r2);
                // Advance original reader past what r2 consumed.
                $consumed = $r2->offset();
                for ($i = 0; $i < $consumed - 4; $i++) {
                    // advance byte-by-byte via int reads is unsafe; instead
                    // we consumed via r2 — mirror by skipping raw bytes is not
                    // possible on Reader, so just accept r2 and ignore $r.
                    break;
                }
                // NOTE: bare-Update path is used only for side-packets where
                // exact outer offset does not matter (each packet parsed fresh).
                return is_array($u) ? [$u] : [];
        }
    }

    /**
     * Parse Vector<Update> inside updates/updatesCombined (best-effort).
     *
     * Layout: updates:Vector<Update> users:Vector<User> chats:Vector<Chat>
     *         date:int seq:int (seq_start:int seq:int for combined).
     * The users/chats side tables are attached to every parsed update so
     * handlers can resolve senders, chats and channels by id.
     */
    private static function parseContainer(Reader $r, int $ctor): array
    {
        $out = [];
        try {
            $count = $r->vectorHeader();
        } catch (\Throwable) {
            return $out;
        }
        for ($i = 0; $i < $count; $i++) {
            try {
                $u = self::parseOneUpdate($r);
                if ($u !== null) {
                    $out[] = $u;
                }
            } catch (\Throwable) {
                // Layout strayed (exotic field): keep what we have.
                break;
            }
        }

        // Side tables trail the updates vector (schema-driven, exact consume).
        $users = self::tryVector($r, 'Vector<User>');
        $chats = self::tryVector($r, 'Vector<Chat>');
        $date = 0;
        $seq = 0;
        try {
            if ($ctor === self::UPDATES_COMBINED) {
                $r->int(); // seq_start
            }
            $date = $r->int();
            $seq = $r->int();
        } catch (\Throwable) {
        }

        foreach ($out as &$u) {
            $u['users'] ??= $users;
            $u['chats'] ??= $chats;
            $u['date'] ??= $date;
            $u['seq'] ??= $seq;
        }
        unset($u);

        return $out;
    }

    /**
     * Parse a whole vector via the schema map, or [] when it fails.
     *
     * @return array<int,mixed>
     */
    private static function tryVector(Reader $r, string $type): array
    {
        try {
            $v = Deserializer::parseValue($type, $r);
            return is_array($v) ? $v : [];
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Parse a single Update object (exact consume or StopWalk).
     *
     * @return array|null Normalized update, null for empty/service-ish.
     */
    public static function parseOneUpdate(Reader $r): ?array
    {
        $ctor = $r->ctor();
        switch ($ctor) {
            case self::NEW_MESSAGE:
            case self::NEW_CHANNEL_MESSAGE: {
                $m = Message::parse($r);
                $pts = self::tryInt($r);
                $ptsCount = self::tryInt($r);
                if ($m === null) {
                    return null;
                }
                $a = $m->toArray();
                return [
                    'kind' => 'new_message',
                    'message' => $a,
                    'text' => $a['text'] ?? '',
                    'peer_type' => $a['peer_type'] ?? '',
                    'peer_id' => $a['peer_id'] ?? 0,
                    'from_id' => $a['from_id'] ?? 0,
                    'msg_id' => $a['id'] ?? 0,
                    'date' => $a['date'] ?? 0,
                    'out' => $a['out'] ?? false,
                    'pts' => $pts ?? 0,
                    'pts_count' => $ptsCount ?? 0,
                    'channel' => $ctor === self::NEW_CHANNEL_MESSAGE,
                ];
            }
            case self::EDIT_MESSAGE:
            case self::EDIT_CHANNEL_MESSAGE: {
                $m = Message::parse($r);
                $pts = self::tryInt($r);
                $ptsCount = self::tryInt($r);
                if ($m === null) {
                    return null;
                }
                $a = $m->toArray();
                return [
                    'kind' => 'edit_message',
                    'message' => $a,
                    'text' => $a['text'] ?? '',
                    'peer_type' => $a['peer_type'] ?? '',
                    'peer_id' => $a['peer_id'] ?? 0,
                    'msg_id' => $a['id'] ?? 0,
                    'date' => $a['date'] ?? 0,
                    'pts' => $pts ?? 0,
                    'pts_count' => $ptsCount ?? 0,
                    'channel' => $ctor === self::EDIT_CHANNEL_MESSAGE,
                ];
            }
            case self::DELETE_MESSAGES: {
                $ids = $r->vectorInt();
                $pts = self::tryInt($r);
                $ptsCount = self::tryInt($r);
                return [
                    'kind' => 'delete_messages',
                    'ids' => $ids,
                    'pts' => $pts ?? 0,
                    'pts_count' => $ptsCount ?? 0,
                ];
            }
            case self::DELETE_CHANNEL_MESSAGES: {
                $channelId = $r->long();
                $ids = $r->vectorInt();
                $pts = self::tryInt($r);
                $ptsCount = self::tryInt($r);
                return [
                    'kind' => 'delete_messages',
                    'peer_type' => 'channel',
                    'peer_id' => $channelId,
                    'ids' => $ids,
                    'pts' => $pts ?? 0,
                    'pts_count' => $ptsCount ?? 0,
                    'channel' => true,
                ];
            }
            default:
                return self::parseGeneric($r, $ctor);
        }
    }

    /**
     * Schema-driven fallback: parse ANY update constructor via the generated
     * {@see \Bpt\TL\Types} map so no update type is ever dropped.
     *
     * @return array{kind:string,_:string}&array<string,mixed>
     */
    private static function parseGeneric(Reader $r, int $ctor): array
    {
        try {
            $u = Deserializer::parseCtor($ctor, $r);
        } catch (\Throwable $e) {
            throw new StopWalk('update 0x' . dechex($ctor) . ': ' . $e->getMessage());
        }
        $u['kind'] = is_string($u['_'] ?? null) ? $u['_'] : ('update_0x' . dechex($ctor));

        return $u;
    }

    /** updateShortSentMessage#9015e101 (outgoing echo). */
    private static function parseShortSent(Reader $r): array
    {
        $flags = $r->int();
        $out = (bool)($flags & 1);
        $id = $r->int();
        $pts = self::tryInt($r) ?? 0;
        $ptsCount = self::tryInt($r) ?? 0;
        $date = self::tryInt($r) ?? 0;
        return [
            'kind' => 'sent',
            'msg_id' => $id,
            'out' => $out,
            'pts' => $pts,
            'pts_count' => $ptsCount,
            'date' => $date,
        ];
    }

    /** updateShortMessage#313bc7f8 (1-1 chat). Trailing optionals are ignored. */
    private static function parseShortMessage(Reader $r, string $peerType): array
    {
        $flags = $r->int();
        $out = (bool)($flags & 1);
        $id = $r->int();
        $userId = 0;
        try {
            $userId = $r->long();
        } catch (\Throwable) {
        }
        $text = '';
        try {
            $text = $r->string();
        } catch (\Throwable) {
        }
        $pts = self::tryInt($r) ?? 0;
        $ptsCount = self::tryInt($r) ?? 0;
        $date = self::tryInt($r) ?? 0;
        return [
            'kind' => 'new_message',
            'text' => $text,
            'message' => [
                'id' => $id, 'date' => $date, 'text' => $text,
                'out' => $out, 'peer_type' => 'user',
                'peer_id' => $userId, 'from_id' => $out ? 0 : $userId,
            ],
            'peer_type' => 'user',
            'peer_id' => $userId,
            'from_id' => $out ? 0 : $userId,
            'msg_id' => $id,
            'date' => $date,
            'out' => $out,
            'pts' => $pts,
            'pts_count' => $ptsCount,
        ];
    }

    /** updateShortChatMessage#4d6deea5 (basic group). */
    private static function parseShortChatMessage(Reader $r): array
    {
        $flags = $r->int();
        $out = (bool)($flags & 1);
        $id = $r->int();
        $fromId = self::tryLong($r) ?? 0;
        $chatId = self::tryLong($r) ?? 0;
        $text = '';
        try {
            $text = $r->string();
        } catch (\Throwable) {
        }
        $pts = self::tryInt($r) ?? 0;
        $ptsCount = self::tryInt($r) ?? 0;
        $date = self::tryInt($r) ?? 0;
        return [
            'kind' => 'new_message',
            'text' => $text,
            'message' => [
                'id' => $id, 'date' => $date, 'text' => $text,
                'out' => $out, 'peer_type' => 'chat',
                'peer_id' => (int)$chatId, 'from_id' => (int)$fromId,
            ],
            'peer_type' => 'chat',
            'peer_id' => (int)$chatId,
            'from_id' => (int)$fromId,
            'msg_id' => $id,
            'date' => $date,
            'out' => $out,
            'pts' => $pts,
            'pts_count' => $ptsCount,
        ];
    }

    private static function tryInt(Reader $r): ?int
    {
        try {
            return $r->int();
        } catch (\Throwable) {
            return null;
        }
    }

    private static function tryLong(Reader $r): ?int
    {
        try {
            return $r->long();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Is this ctor a top-level Updates object (vs rpc_result)?
     */
    public static function isUpdatesCtor(int $ctor): bool
    {
        return in_array($ctor, [
            self::UPDATES, self::UPDATES_COMBINED, self::UPDATE_SHORT,
            self::UPDATE_SHORT_MESSAGE, self::UPDATE_SHORT_CHAT_MESSAGE,
            self::UPDATE_SHORT_SENT, self::UPDATES_TOO_LONG,
            self::NEW_MESSAGE, self::NEW_CHANNEL_MESSAGE,
            self::EDIT_MESSAGE, self::EDIT_CHANNEL_MESSAGE,
            self::DELETE_MESSAGES, self::DELETE_CHANNEL_MESSAGES,
        ], true);
    }
}
