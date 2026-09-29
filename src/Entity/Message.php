<?php

declare(strict_types=1);

namespace Bpt\Entity;

use Bpt\TL\Ctors;
use Bpt\TL\Reader;

/**
 * Minimal message DTO with full-consume parsing.
 *
 * Covers message#95ef6f2b (and legacy 204 message#eabcdd4d) as well as
 * messageService for the common service actions. Layout:
 * ctor, flags, flags2, id, from_id?, peer_id, saved_peer_id?, fwd?,
 * via_bot?, reply_to?, date, message, media?, markup?, entities?, ...
 *
 * Exotic payloads (media other than empty, keyboards, unknown actions)
 * throw {@see StopWalk}: the vector walker keeps previously parsed items
 * and stops. Text histories therefore parse completely.
 */
final class Message
{
    public function __construct(
        public readonly int $id,
        public readonly int $date = 0,
        public readonly string $text = '',
        public readonly bool $out = false,
        public readonly string $peerType = '',
        public readonly int $peerId = 0,
        public readonly int $fromId = 0,
    ) {
    }

    /**
     * Parse AND fully consume one Message object.
     *
     * @return self|null Null for empty/service messages (still consumed).
     * @throws StopWalk When the payload cannot be consumed exactly.
     */
    public static function parse(Reader $r): ?self
    {
        $ctor = $r->ctor();
        if ($ctor === Ctors::MESSAGE_EMPTY) {
            $r->int(); // id
            return null;
        }
        if ($ctor === Ctors::MESSAGE_SERVICE) {
            self::skipService($r);
            return null;
        }
        $legacy = $ctor === 0xEABCDD4D;
        if (!$legacy && $ctor !== 0x95EF6F2B) {
            throw new \RuntimeException('expected message, got 0x' . dechex($ctor));
        }
        $flags = $r->int();
        $flags2 = $r->int();
        $out = (bool)($flags & 2);
        $id = $r->int();
        $fromId = 0;
        if ($flags & ($legacy ? (1 << 28) : (1 << 8))) {
            [, $fromId] = self::readPeer($r);
        }
        if (!$legacy && ($flags & (1 << 29))) {
            $r->int();
        } // from_boosts_applied
        if (!$legacy && ($flags2 & (1 << 12))) {
            $r->string();
        } // from_rank
        [$pt, $pid] = self::readPeer($r);
        if (!$legacy && ($flags & (1 << 28))) {
            self::skipPeer($r);
        } // saved_peer_id (current layers only)
        if ($flags & (1 << 2)) {
            self::skipFwd($r);
        }
        if ($flags & (1 << 11)) {
            $r->long();
        } // via_bot_id
        if (!$legacy && ($flags2 & 1)) {
            $r->long();
        } // via_business_bot_id
        if (!$legacy && ($flags2 & (1 << 19))) {
            self::skipPeer($r);
        } // guestchat_via_from
        if ($flags & (1 << 3)) {
            self::skipReply($r);
        }
        $date = $r->int();
        $text = $r->string();
        // ---- trailing (must all be consumed for vector walks) ----
        if ($flags & (1 << 9)) {
            self::skipMedia($r);
        }
        if ($flags & (1 << 6)) {
            throw new StopWalk('reply_markup');
        }
        if ($flags & (1 << 7)) {
            self::skipEntities($r);
        }
        if ($flags & (1 << 10)) {
            $r->int();
            $r->int();
        } // views, forwards
        if ($flags & (1 << 23)) {
            self::skipReplies($r);
        }
        if ($flags & (1 << 15)) {
            $r->int();
        } // edit_date
        if ($flags & (1 << 16)) {
            $r->string();
        } // post_author
        if ($flags & (1 << 17)) {
            $r->long();
        } // grouped_id
        if ($flags & (1 << 20)) {
            self::skipReactions($r);
        }
        if ($flags & (1 << 22)) {
            self::skipRestriction($r);
        }
        if ($flags & (1 << 25)) {
            $r->int();
        } // ttl_period
        if ($flags & (1 << 30)) {
            $r->int();
        } // quick_reply_shortcut_id
        if (!$legacy && $flags2 & ((1 << 2) | (1 << 5) | (1 << 6) | (1 << 7) | (1 << 10))) {
            throw new StopWalk('flags2 payload');
        }
        return new self($id, $date, $text, $out, $pt, $pid, $fromId);
    }

    /** @return array{0:string,1:int} */
    public static function readPeer(Reader $r): array
    {
        $ctor = $r->ctor();
        return match ($ctor) {
            Ctors::PEER_USER => ['user', $r->long()],
            Ctors::PEER_CHAT => ['chat', $r->long()],
            Ctors::PEER_CHANNEL => ['channel', $r->long()],
            default => throw new \RuntimeException('bad peer 0x' . dechex($ctor)),
        };
    }

    public static function skipPeer(Reader $r): void
    {
        self::readPeer($r);
    }

    // ------------------------------------------------------------ skips

    private static function skipService(Reader $r): void
    {
        $flags = $r->int();
        $r->int(); // id
        if ($flags & (1 << 8)) {
            self::skipPeer($r);
        }
        self::skipPeer($r); // peer_id
        if ($flags & (1 << 28)) {
            self::skipPeer($r);
        }
        if ($flags & (1 << 3)) {
            self::skipReply($r);
        }
        $r->int(); // date
        $action = $r->ctor();
        match ($action) {
            0xB6AEF7B0, 0x9FBAB604, 0xF3F25F76, 0x94BD38ED => null, // empty/clear/signup/pin
            0xBD47CBAD => (function () use ($r) { // chatCreate
                $r->string();
                $r->vectorLong();
            })(),
            0xB5A1CE5A, 0x95D2AC92 => $r->string(), // editTitle / channelCreate
            0x15CEFD00 => $r->vectorLong(), // chatAddUser
            0xA43F30CC, 0x031224C3 => $r->long(), // deleteUser / joinedByLink
            default => throw new StopWalk('action 0x' . dechex($action)),
        };
        if ($flags & (1 << 20)) {
            self::skipReactions($r);
        }
        if ($flags & (1 << 25)) {
            $r->int();
        }
    }

    /** messageFwdHeader#4e4df4bb. */
    private static function skipFwd(Reader $r): void
    {
        $flags = $r->int();
        if ($flags & 1) {
            self::skipPeer($r);
        } // from_id
        if ($flags & 32) {
            $r->string();
        } // from_name
        $r->int(); // date
        if ($flags & 4) {
            $r->int();
        } // channel_post
        if ($flags & 8) {
            $r->string();
        } // post_author
        if ($flags & 16) {
            self::skipPeer($r);
            $r->int();
        } // saved_from_peer + msg_id
        if ($flags & 256) {
            self::skipPeer($r);
        } // saved_from_id
        if ($flags & 512) {
            $r->string();
        } // saved_from_name
        if ($flags & 1024) {
            $r->int();
        } // saved_date
        if ($flags & 64) {
            $r->string();
        } // psa_type
    }

    /** messageReplyHeader#1b97dd66. */
    private static function skipReply(Reader $r): void
    {
        $flags = $r->int();
        if ($flags & 16) {
            $r->int();
        } // reply_to_msg_id
        if ($flags & 1) {
            self::skipPeer($r);
        } // reply_to_peer_id
        if ($flags & 32) {
            self::skipFwd($r);
        } // reply_from
        if ($flags & 256) {
            self::skipMedia($r);
        } // reply_media
        if ($flags & 2) {
            $r->int();
        } // reply_to_top_id
        if ($flags & 64) {
            $r->string();
        } // quote_text
        if ($flags & 128) {
            self::skipEntities($r);
        } // quote_entities
        if ($flags & 1024) {
            $r->int();
        } // quote_offset
        if ($flags & 2048) {
            $r->int();
        } // todo_item_id
        if ($flags & 4096) {
            $r->bytes();
        } // poll_option
    }

    private static function skipMedia(Reader $r): void
    {
        if ($r->ctor() !== 0x3DED6320) { // messageMediaEmpty
            throw new StopWalk('media');
        }
    }

    private static function skipEntities(Reader $r): void
    {
        $n = $r->vectorHeader();
        for ($i = 0; $i < $n; $i++) {
            $ctor = $r->ctor();
            $r->int();
            $r->int(); // offset, length
            match ($ctor) {
                0x76A6D327 => $r->string(), // textUrl
                0x73924BE0 => $r->string(), // pre: language
                0xDC7B1140 => $r->long(), // mentionName: user_id
                0xC8CF05F8 => $r->long(), // customEmoji: document_id
                0xFA04579D, 0xBD610BC9, 0x1B2286B8 => null, // bare offset+length
                default => throw new StopWalk('entity 0x' . dechex($ctor)),
            };
        }
    }

    private static function skipReplies(Reader $r): void
    {
        $flags = $r->int(); // messageReplies#83d60fc2
        $r->int();
        $r->int(); // replies, replies_pts
        if ($flags & 2) {
            $n = $r->vectorHeader();
            for ($i = 0; $i < $n; $i++) {
                self::skipPeer($r);
            }
        }
        if ($flags & 1) {
            $r->long();
        } // channel_id
        if ($flags & 4) {
            $r->int();
        } // max_id
        if ($flags & 8) {
            $r->int();
        } // read_max_id
    }

    private static function skipReactions(Reader $r): void
    {
        $flags = $r->int();
        $n = $r->vectorHeader(); // results: Vector<ReactionCount>
        for ($i = 0; $i < $n; $i++) {
            $f = $r->int();
            if ($f & 1) {
                $r->int();
            }
            $ctor = $r->ctor();
            match ($ctor) {
                0x79F5D419, 0x523DA4EB => null, // empty / paid
                0x1B2286B8 => $r->string(), // emoji
                0x8935FC73 => $r->long(), // customEmoji
                default => throw new StopWalk('reaction 0x' . dechex($ctor)),
            };
            $r->int(); // count
        }
        if ($flags & 2) {
            throw new StopWalk('recent_reactions');
        }
        if ($flags & 16) {
            throw new StopWalk('top_reactors');
        }
    }

    private static function skipRestriction(Reader $r): void
    {
        $n = $r->vectorHeader();
        for ($i = 0; $i < $n; $i++) {
            $r->ctor();
            $r->string();
            $r->string();
            $r->string();
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id, 'date' => $this->date, 'text' => $this->text,
            'out' => $this->out, 'peer_type' => $this->peerType,
            'peer_id' => $this->peerId, 'from_id' => $this->fromId,
        ];
    }
}
