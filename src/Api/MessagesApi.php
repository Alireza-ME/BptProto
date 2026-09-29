<?php

declare(strict_types=1);

namespace Bpt\Api;

use Bpt\Session\EncryptedSession;
use Bpt\TL\Builder;
use Bpt\TL\Peer;

/**
 * Handwritten messages.* methods (text flow; no media upload yet).
 */
final class MessagesApi
{
    public function __construct(
        private readonly EncryptedSession $session,
        private readonly int $apiId,
        private readonly int $layer,
    ) {
    }

    /**
     * messages.sendMessage#545cd15a (flags=0 subset + common options).
     *
     * @param array{silent?:bool,no_webpage?:bool} $opts
     */
    public function sendMessage(string $peer, string $text, array $opts = []): string
    {
        $flags = 0;
        if (!empty($opts['no_webpage'])) {
            $flags |= (1 << 1);
        }
        if (!empty($opts['silent'])) {
            $flags |= (1 << 5);
        }
        $body = Builder::ctor(0x545CD15A)->int($flags)->rawBlob($peer)
            ->string($text)->long(random_int(1, PHP_INT_MAX))->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** Shortcut for Saved Messages. */
    public function sendMessageToSelf(string $text, array $opts = []): string
    {
        return $this->sendMessage(Peer::self(), $text, $opts);
    }

    /** messages.getHistory#4423e6c5. */
    public function getHistory(string $peer, int $limit = 20, int $offsetId = 0, int $addOffset = 0, int $maxId = 0, int $minId = 0): string
    {
        $body = Builder::ctor(0x4423E6C5)->rawBlob($peer)
            ->int($offsetId)->int(0)->int($addOffset)->int($limit)
            ->int($maxId)->int($minId)->long(0)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** messages.getDialogs#a0f4cb4f (flags=0, no folder, empty offset peer). */
    public function getDialogs(int $limit = 20, int $offsetId = 0, int $offsetDate = 0): string
    {
        $body = Builder::ctor(0xA0F4CB4F)->int(0)
            ->int($offsetDate)->int($offsetId)->rawBlob(Peer::empty())
            ->int($limit)->long(0)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** messages.getMessages#63c66506 id:Vector<InputMessage>. */
    public function getMessagesById(array $msgIds, string $peerForChannel = ''): string
    {
        // inputMessageID#a676a322 for plain ids.
        $items = [];
        foreach ($msgIds as $id) {
            $items[] = Builder::ctor(0xA676A322)->int((int)$id)->build();
        }
        $body = Builder::ctor(0x63C66506)->vector($items)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** messages.forwardMessages#13704a7c (flags=0 subset). */
    public function forwardMessages(string $fromPeer, array $msgIds, string $toPeer): string
    {
        $random = [];
        foreach ($msgIds as $_) {
            $random[] = random_int(1, PHP_INT_MAX);
        }
        $ids = [];
        foreach ($msgIds as $id) {
            $ids[] = (int)$id;
        }
        $body = Builder::raw(
            Builder::ctor(0x13704A7C)->int(0)->build()
            . $fromPeer
            . Builder::raw('')->vectorInt($ids)->build()
            . Builder::raw('')->vectorLong($random)->build()
            . $toPeer
        )->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** messages.deleteMessages#e58e95d2 revoke:flags.0?true. */
    public function deleteMessages(array $msgIds, bool $revoke = true): string
    {
        $flags = $revoke ? 1 : 0;
        $body = Builder::raw(
            Builder::ctor(0xE58E95D2)->int($flags)->build()
            . Builder::raw('')->vectorInt(array_map('intval', $msgIds))->build()
        )->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** messages.editMessage#51e842e1 message:flags.11?string. */
    public function editMessage(string $peer, int $msgId, string $text): string
    {
        $flags = (1 << 11); // message present
        $body = Builder::ctor(0x51E842E1)->int($flags)->rawBlob($peer)
            ->int($msgId)->string($text)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** messages.readHistory#e306d3a. */
    public function readHistory(string $peer, int $maxId = 0): string
    {
        $body = Builder::ctor(0x0E306D3A)->rawBlob($peer)->int($maxId)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** messages.deleteHistory#b08f922a revoke:flags.1?true. */
    public function deleteHistory(string $peer, bool $revoke = true, int $maxId = 0): string
    {
        $flags = $revoke ? (1 << 1) : 0;
        $body = Builder::ctor(0xB08F922A)->int($flags)->rawBlob($peer)->int($maxId)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** messages.search#29ee847a peer+q (global when peer=empty). */
    public function search(string $peer, string $q, int $limit = 20, int $offsetId = 0, int $maxId = 0, int $minId = 0): string
    {
        // flags=0 → from_id/saved_* absent; filter=inputMessagesFilterEmpty#57e2f66c
        $filter = Builder::ctor(0x57E2F66C)->build();
        $body = Builder::ctor(0x29EE847A)->int(0)->rawBlob($peer)
            ->string($q)->rawBlob($filter)
            ->int(0)->int(0)->int($offsetId)->int(0)->int($limit)
            ->int($maxId)->int($minId)->long(0)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /**
     * messages.sendMedia#0330e77f flags=0 subset.
     *
     * @param string $peerBlob  Packed InputPeer.
     * @param string $mediaBlob Packed InputMedia (see Peer::inputMediaPhoto/Document).
     */
    public function sendMedia(string $peerBlob, string $mediaBlob, string $caption = '', array $opts = []): string
    {
        $flags = 0;
        if (!empty($opts['silent'])) {
            $flags |= (1 << 5);
        }
        $body = Builder::ctor(0x0330E77F)->int($flags)->rawBlob($peerBlob)
            ->rawBlob($mediaBlob)->string($caption)
            ->long(random_int(1, PHP_INT_MAX))->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }
}
