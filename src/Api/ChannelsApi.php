<?php

declare(strict_types=1);

namespace Bpt\Api;

use Bpt\Session\EncryptedSession;
use Bpt\TL\Builder;

/**
 * Handwritten channels.* methods.
 */
final class ChannelsApi
{
    public function __construct(
        private readonly EncryptedSession $session,
        private readonly int $apiId,
        private readonly int $layer,
    ) {
    }

    /** channels.joinChannel#24b524c5 channel:InputChannel = Updates. */
    public function joinChannel(string $inputChannel): string
    {
        $body = Builder::ctor(0x24B524C5)->rawBlob($inputChannel)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** channels.leaveChannel#f836aa95 channel:InputChannel = Updates. */
    public function leaveChannel(string $inputChannel): string
    {
        $body = Builder::ctor(0xF836AA95)->rawBlob($inputChannel)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** channels.getChannels#a7f6bbb id:Vector<InputChannel> = messages.Chats. */
    public function getChannels(array $inputChannels): string
    {
        $body = Builder::ctor(0x0A7F6BBB)->vector($inputChannels)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** channels.getFullChannel#8736a09 channel:InputChannel = messages.ChatFull. */
    public function getFullChannel(string $inputChannel): string
    {
        $body = Builder::ctor(0x08736A09)->rawBlob($inputChannel)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /** channels.inviteToChannel#c9e33d54 channel + users:Vector<InputUser>. */
    public function inviteToChannel(string $inputChannel, array $inputUsers): string
    {
        $body = Builder::raw(
            Builder::ctor(0xC9E33D54)->rawBlob($inputChannel)->build()
            . Builder::raw('')->vector($inputUsers)->build()
        )->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }

    /**
     * channels.getParticipants#77ced9d0 with channelParticipantsRecent#de3f3c79.
     *
     * @return string channels.ChannelParticipants
     */
    public function getParticipants(string $inputChannel, int $limit = 20, int $offset = 0): string
    {
        $filter = Builder::ctor(0xDE3F3C79)->build(); // channelParticipantsRecent
        $body = Builder::ctor(0x77CED9D0)->rawBlob($inputChannel)
            ->rawBlob($filter)->int($offset)->int($limit)->long(0)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }
}
