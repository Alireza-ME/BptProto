<?php

declare(strict_types=1);

namespace Bpt\Facade\Concerns;

use Bpt\Entity\Message;
use Bpt\Entity\User;
use Bpt\TL\Reader;

/**
 * Best-effort parsers shared by the messages and updates groups.
 *
 * All of them tolerate shape drift between API layers: an unknown trailing
 * field stops the loop instead of throwing, so a partial result is still
 * useful.
 */
trait ParsesMessages
{
    /**
     * Parse messages.Messages / Slice / channelMessages (best-effort).
     *
     * @param callable(User):void|null $onUser
     * @return array{messages:array[],users:array[],count?:int}
     */
    private static function parseMessagesResponse(string $raw, ?callable $onUser = null): array
    {
        $r = Reader::of($raw);
        $ctor = $r->ctor();
        if ($ctor === 0x74535F21) { // messagesNotModified
            return ['messages' => [], 'users' => [], 'not_modified' => true, 'count' => $r->int()];
        }
        $count = null;
        if ($ctor === 0x5F206716 || $ctor === 0x3A54685E) { // messagesSlice (+legacy 204)
            $flags = $r->int();
            $count = $r->int();
            if ($flags & 1) {
                $r->int();
            } // next_rate
            if ($flags & 4) {
                $r->int();
            } // offset_id_offset
        } elseif ($ctor === 0xC776BA4E) { // channelMessages: flags,pts,count,offset?
            $flags = $r->int();
            $r->int();
            $count = $r->int();
            if ($flags & 4) {
                $r->int();
            }
        } elseif ($ctor !== 0x1D73E7EA) { // messages
            throw new \RuntimeException('unexpected messages response 0x' . dechex($ctor));
        }
        $messages = self::parseMessageVectorBestEffort($r);
        $out = ['messages' => $messages, 'users' => []];
        if ($count !== null) {
            $out['count'] = $count;
        }
        // topics must be empty to continue; chats must be empty to reach users.
        try {
            if ($r->vectorHeader() !== 0) {
                return $out;
            }
            if ($r->vectorHeader() !== 0) {
                return $out;
            }
            $users = User::parseVector($r);
            if ($onUser !== null) {
                foreach ($users as $u) {
                    $onUser($u);
                }
            }
            $out['users'] = array_map(static fn(User $u) => $u->toArray(), $users);
        } catch (\Throwable) {
        }
        return $out;
    }

    /** @return array[] */
    private static function parseMessageVectorBestEffort(Reader $r): array
    {
        $out = [];
        try {
            $n = $r->vectorHeader();
        } catch (\Throwable) {
            return $out;
        }
        for ($i = 0; $i < $n; $i++) {
            try {
                $m = Message::parse($r);
                if ($m !== null) {
                    $out[] = $m->toArray();
                }
            } catch (\Throwable) {
                break;
            }
        }
        return $out;
    }

    /** Extract sent message id from Updates (updateMessageID or ShortSent). */
    private static function extractSentId(string $updates): int
    {
        $r = Reader::of($updates);
        $ctor = $r->ctor();
        if ($ctor === 0x9015E101) { // updateShortSentMessage
            $r->int();
            return $r->int();
        }
        if ($ctor === 0x74AE4240) { // updates: first update often updateMessageID#4e90bfd6
            $n = $r->vectorHeader();
            if ($n > 0) {
                $u = $r->ctor();
                if ($u === 0x4E90BFD6) {
                    return $r->int();
                }
            }
            return 0;
        }
        return 0;
    }
}
