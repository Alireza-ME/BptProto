<?php

declare(strict_types=1);

namespace Bpt\Entity;

use Bpt\TL\Ctors;
use Bpt\TL\Reader;

/**
 * Minimal dialog DTO (dialog#fc89f7f3 subset).
 *
 * Parsing is best-effort: dialogs carrying drafts or exotic notify settings
 * stop the vector walk (caller returns what was parsed so far).
 */
final class Dialog
{
    public function __construct(
        public readonly string $peerType = '',
        public readonly int $peerId = 0,
        public readonly int $topMessage = 0,
        public readonly int $unreadCount = 0,
        public readonly bool $pinned = false,
    ) {
    }

    public static function parse(Reader $r): self
    {
        $ctor = $r->ctor();
        $legacy = $ctor === 0xD58A08C6;
        if (!$legacy && $ctor !== 0xFC89F7F3) {
            throw new \RuntimeException('expected dialog, got 0x' . dechex($ctor));
        }
        $flags = $r->int();
        if ($flags & (1 << 1)) {
            throw new \RuntimeException('dialog with draft: stop walk');
        }
        $pinned = (bool)($flags & 4);
        [$pt, $pid] = Message::readPeer($r);
        $top = $r->int();
        $r->int();
        $r->int(); // read_inbox_max_id, read_outbox_max_id
        $unread = $r->int();
        $r->int();
        $r->int(); // mentions, reactions
        if (!$legacy) {
            $r->int();
        } // unread_poll_votes_count (absent on legacy layers)
        self::skipNotifySettings($r);
        if ($flags & 1) {
            $r->int();
        } // pts
        // draft absent (checked), folder_id / ttl_period trailing
        if ($flags & (1 << 4)) {
            $r->int();
        }
        if ($flags & (1 << 5)) {
            $r->int();
        }
        return new self($pt, $pid, $top, $unread, $pinned);
    }

    /** @return self[] parsed until first failure */
    public static function parseVectorBestEffort(Reader $r): array
    {
        $out = [];
        try {
            $n = $r->vectorHeader();
        } catch (\Throwable) {
            return $out;
        }
        for ($i = 0; $i < $n; $i++) {
            try {
                $out[] = self::parse($r);
            } catch (\Throwable) {
                break;
            }
        }
        return $out;
    }

    public static function skipNotifySettings(Reader $r): void
    {
        $ctor = $r->ctor();
        if ($ctor === 0x99622C0C) { // peerNotifySettings
            $flags = $r->int();
            if ($flags & 1) {
                $r->int();
            } // show_previews Bool
            if ($flags & 2) {
                $r->int();
            } // silent Bool
            if ($flags & 4) {
                $r->int();
            } // mute_until
            foreach ([8, 16, 32, 256, 512, 1024] as $bit) {
                if ($flags & $bit) {
                    self::skipNotificationSound($r);
                }
            }
            if ($flags & 64) {
                $r->int();
            } // stories_muted Bool
            if ($flags & 128) {
                $r->int();
            } // stories_hide_sender Bool
            return;
        }
        throw new \RuntimeException('unknown notifySettings 0x' . dechex($ctor));
    }

    private static function skipNotificationSound(Reader $r): void
    {
        $ctor = $r->ctor();
        // default / none: bare ctors
        if ($ctor === 0x97E8BEBE || $ctor === 0x6F0C34DF) {
            return;
        }
        // local#830b9ae4 title:data:string
        if ($ctor === 0x830B9AE4) {
            $r->string();
            $r->string();
            return;
        }
        // ringtone#ff6c8049 id:long
        if ($ctor === 0xFF6C8049) {
            $r->long();
            return;
        }
        throw new \RuntimeException('unknown sound 0x' . dechex($ctor));
    }

    public function toArray(): array
    {
        return [
            'peer_type' => $this->peerType, 'peer_id' => $this->peerId,
            'top_message' => $this->topMessage, 'unread_count' => $this->unreadCount,
            'pinned' => $this->pinned,
        ];
    }
}
