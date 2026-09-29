<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every stories.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Stories extends Group
{

    /**
     * stories.activateStealthMode#57bbd166 = Updates.
     */
    public function activateStealthMode(bool $past = false, bool $future = false): mixed
    {
        $flags = 0;
        if ($past) { $flags |= (1 << 0); }
        if ($future) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x57BBD166);
        $b->int($flags);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * stories.canSendStory#30eb63f0 = stories.CanSendStoryCount.
     */
    public function canSendStory(mixed $peer): mixed
    {
        $b = Builder::ctor(0x30EB63F0);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.CanSendStoryCount');
    }

    /**
     * stories.createAlbum#a36396e5 = StoryAlbum.
     */
    public function createAlbum(mixed $peer, string $title, array $stories): mixed
    {
        $b = Builder::ctor(0xA36396E5);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$title);
        $b->vectorInt($stories);
        return Deserializer::parse($this->client->rpc($b->build()), 'StoryAlbum');
    }

    /**
     * stories.deleteAlbum#8d3456d0 = Bool.
     */
    public function deleteAlbum(mixed $peer, int $album_id): mixed
    {
        $b = Builder::ctor(0x8D3456D0);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$album_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * stories.deleteStories#ae59db5f = Vector<int>.
     */
    public function deleteStories(mixed $peer, array $id): mixed
    {
        $b = Builder::ctor(0xAE59DB5F);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<int>');
    }

    /**
     * stories.editStory#2c63a72b = Updates.
     */
    public function editStory(mixed $peer, int $id, ?string $media = null, ?array $media_areas = null, ?string $caption = null, ?array $entities = null, ?array $privacy_rules = null, ?string $music = null): mixed
    {
        $flags = 0;
        if ($media !== null) { $flags |= (1 << 0); }
        if ($media_areas !== null) { $flags |= (1 << 3); }
        if ($caption !== null) { $flags |= (1 << 1); }
        if ($entities !== null) { $flags |= (1 << 1); }
        if ($privacy_rules !== null) { $flags |= (1 << 2); }
        if ($music !== null) { $flags |= (1 << 4); }
        $b = Builder::ctor(0x2C63A72B);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        if ($media !== null) { $b->rawBlob($media); }
        if ($media_areas !== null) { $b->vector($media_areas); }
        if ($caption !== null) { $b->string((string)$caption); }
        if ($entities !== null) { $b->vector($entities); }
        if ($privacy_rules !== null) { $b->vector($privacy_rules); }
        if ($music !== null) { $b->rawBlob($music); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * stories.exportStoryLink#7b8def20 = ExportedStoryLink.
     */
    public function exportStoryLink(mixed $peer, int $id): mixed
    {
        $b = Builder::ctor(0x7B8DEF20);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        return Deserializer::parse($this->client->rpc($b->build()), 'ExportedStoryLink');
    }

    /**
     * stories.getAlbumStories#ac806d61 = stories.Stories.
     */
    public function getAlbumStories(mixed $peer, int $album_id, int $offset, int $limit): mixed
    {
        $b = Builder::ctor(0xAC806D61);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$album_id);
        $b->int((int)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.Stories');
    }

    /**
     * stories.getAlbums#25b3eac7 = stories.Albums.
     */
    public function getAlbums(mixed $peer, int $hash): mixed
    {
        $b = Builder::ctor(0x25B3EAC7);
        $b->rawBlob($this->peerBlob($peer));
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.Albums');
    }

    /**
     * stories.getAllReadPeerStories#9b5ae7f9 = Updates.
     */
    public function getAllReadPeerStories(): mixed
    {
        $b = Builder::ctor(0x9B5AE7F9);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * stories.getAllStories#eeb0d625 = stories.AllStories.
     */
    public function getAllStories(bool $next = false, bool $hidden = false, ?string $state = null): mixed
    {
        $flags = 0;
        if ($next) { $flags |= (1 << 1); }
        if ($hidden) { $flags |= (1 << 2); }
        if ($state !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xEEB0D625);
        $b->int($flags);
        if ($state !== null) { $b->string((string)$state); }
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.AllStories');
    }

    /**
     * stories.getChatsToSend#a56a8b60 = messages.Chats.
     */
    public function getChatsToSend(): mixed
    {
        $b = Builder::ctor(0xA56A8B60);
        return Deserializer::parse($this->client->rpc($b->build()), 'messages.Chats');
    }

    /**
     * stories.getPeerMaxIDs#78499170 = Vector<RecentStory>.
     */
    public function getPeerMaxIDs(array $id): mixed
    {
        $b = Builder::ctor(0x78499170);
        $b->vector(array_map(fn($x) => $this->peerBlob($x), $id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<RecentStory>');
    }

    /**
     * stories.getPeerStories#2c4ada50 = stories.PeerStories.
     */
    public function getPeerStories(mixed $peer): mixed
    {
        $b = Builder::ctor(0x2C4ADA50);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.PeerStories');
    }

    /**
     * stories.getPinnedStories#5821a5dc = stories.Stories.
     */
    public function getPinnedStories(mixed $peer, int $offset_id, int $limit): mixed
    {
        $b = Builder::ctor(0x5821A5DC);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$offset_id);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.Stories');
    }

    /**
     * stories.getStoriesArchive#b4352016 = stories.Stories.
     */
    public function getStoriesArchive(mixed $peer, int $offset_id, int $limit): mixed
    {
        $b = Builder::ctor(0xB4352016);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$offset_id);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.Stories');
    }

    /**
     * stories.getStoriesByID#5774ca74 = stories.Stories.
     */
    public function getStoriesByID(mixed $peer, array $id): mixed
    {
        $b = Builder::ctor(0x5774CA74);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.Stories');
    }

    /**
     * stories.getStoriesViews#28e16cc8 = stories.StoryViews.
     */
    public function getStoriesViews(mixed $peer, array $id): mixed
    {
        $b = Builder::ctor(0x28E16CC8);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.StoryViews');
    }

    /**
     * stories.getStoryReactionsList#b9b2881f = stories.StoryReactionsList.
     */
    public function getStoryReactionsList(mixed $peer, int $id, int $limit, bool $forwards_first = false, ?string $reaction = null, ?string $offset = null): mixed
    {
        $flags = 0;
        if ($forwards_first) { $flags |= (1 << 2); }
        if ($reaction !== null) { $flags |= (1 << 0); }
        if ($offset !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xB9B2881F);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$id);
        if ($reaction !== null) { $b->rawBlob($reaction); }
        if ($offset !== null) { $b->string((string)$offset); }
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.StoryReactionsList');
    }

    /**
     * stories.getStoryViewsList#7ed23c57 = stories.StoryViewsList.
     */
    public function getStoryViewsList(mixed $peer, int $id, string $offset, int $limit, bool $just_contacts = false, bool $reactions_first = false, bool $forwards_first = false, ?string $q = null): mixed
    {
        $flags = 0;
        if ($just_contacts) { $flags |= (1 << 0); }
        if ($reactions_first) { $flags |= (1 << 2); }
        if ($forwards_first) { $flags |= (1 << 3); }
        if ($q !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x7ED23C57);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($q !== null) { $b->string((string)$q); }
        $b->int((int)$id);
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.StoryViewsList');
    }

    /**
     * stories.incrementStoryViews#b2028afb = Bool.
     */
    public function incrementStoryViews(mixed $peer, array $id): mixed
    {
        $b = Builder::ctor(0xB2028AFB);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * stories.readStories#a556dac8 = Vector<int>.
     */
    public function readStories(mixed $peer, int $max_id): mixed
    {
        $b = Builder::ctor(0xA556DAC8);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$max_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<int>');
    }

    /**
     * stories.reorderAlbums#8535fbd9 = Bool.
     */
    public function reorderAlbums(mixed $peer, array $order): mixed
    {
        $b = Builder::ctor(0x8535FBD9);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($order);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * stories.report#19d8eb45 = ReportResult.
     */
    public function report(mixed $peer, array $id, string $option, string $message): mixed
    {
        $b = Builder::ctor(0x19D8EB45);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        $b->string((string)$option);
        $b->string((string)$message);
        return Deserializer::parse($this->client->rpc($b->build()), 'ReportResult');
    }

    /**
     * stories.searchPosts#d1810907 = stories.FoundStories.
     */
    public function searchPosts(string $offset, int $limit, ?string $hashtag = null, ?string $area = null, mixed $peer = null): mixed
    {
        $flags = 0;
        if ($hashtag !== null) { $flags |= (1 << 0); }
        if ($area !== null) { $flags |= (1 << 1); }
        if ($peer !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0xD1810907);
        $b->int($flags);
        if ($hashtag !== null) { $b->string((string)$hashtag); }
        if ($area !== null) { $b->rawBlob($area); }
        if ($peer !== null) { $b->rawBlob($this->peerBlob($peer)); }
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'stories.FoundStories');
    }

    /**
     * stories.sendReaction#7fd736b2 = Updates.
     */
    public function sendReaction(mixed $peer, int $story_id, string $reaction, bool $add_to_recent = false): mixed
    {
        $flags = 0;
        if ($add_to_recent) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x7FD736B2);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$story_id);
        $b->rawBlob($reaction);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * stories.sendStory#8f9e6898 = Updates.
     */
    public function sendStory(mixed $peer, string $media, array $privacy_rules, int $random_id, bool $pinned = false, bool $noforwards = false, bool $fwd_modified = false, ?array $media_areas = null, ?string $caption = null, ?array $entities = null, ?int $period = null, mixed $fwd_from_id = null, ?int $fwd_from_story = null, ?array $albums = null, ?string $music = null): mixed
    {
        $flags = 0;
        if ($pinned) { $flags |= (1 << 2); }
        if ($noforwards) { $flags |= (1 << 4); }
        if ($fwd_modified) { $flags |= (1 << 7); }
        if ($media_areas !== null) { $flags |= (1 << 5); }
        if ($caption !== null) { $flags |= (1 << 0); }
        if ($entities !== null) { $flags |= (1 << 1); }
        if ($period !== null) { $flags |= (1 << 3); }
        if ($fwd_from_id !== null) { $flags |= (1 << 6); }
        if ($fwd_from_story !== null) { $flags |= (1 << 6); }
        if ($albums !== null) { $flags |= (1 << 8); }
        if ($music !== null) { $flags |= (1 << 9); }
        $b = Builder::ctor(0x8F9E6898);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($media);
        if ($media_areas !== null) { $b->vector($media_areas); }
        if ($caption !== null) { $b->string((string)$caption); }
        if ($entities !== null) { $b->vector($entities); }
        $b->vector($privacy_rules);
        $b->long((int)$random_id);
        if ($period !== null) { $b->int((int)$period); }
        if ($fwd_from_id !== null) { $b->rawBlob($this->peerBlob($fwd_from_id)); }
        if ($fwd_from_story !== null) { $b->int((int)$fwd_from_story); }
        if ($albums !== null) { $b->vectorInt($albums); }
        if ($music !== null) { $b->rawBlob($music); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * stories.startLive#d069ccde = Updates.
     */
    public function startLive(mixed $peer, array $privacy_rules, int $random_id, bool $pinned = false, bool $noforwards = false, bool $rtmp_stream = false, ?string $caption = null, ?array $entities = null, ?bool $messages_enabled = null, ?int $send_paid_messages_stars = null): mixed
    {
        $flags = 0;
        if ($pinned) { $flags |= (1 << 2); }
        if ($noforwards) { $flags |= (1 << 4); }
        if ($rtmp_stream) { $flags |= (1 << 5); }
        if ($caption !== null) { $flags |= (1 << 0); }
        if ($entities !== null) { $flags |= (1 << 1); }
        if ($messages_enabled !== null) { $flags |= (1 << 6); }
        if ($send_paid_messages_stars !== null) { $flags |= (1 << 7); }
        $b = Builder::ctor(0xD069CCDE);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($caption !== null) { $b->string((string)$caption); }
        if ($entities !== null) { $b->vector($entities); }
        $b->vector($privacy_rules);
        $b->long((int)$random_id);
        if ($messages_enabled !== null) { $b->bool((bool)$messages_enabled); }
        if ($send_paid_messages_stars !== null) { $b->long((int)$send_paid_messages_stars); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * stories.toggleAllStoriesHidden#7c2557c4 = Bool.
     */
    public function toggleAllStoriesHidden(bool $hidden): mixed
    {
        $b = Builder::ctor(0x7C2557C4);
        $b->bool((bool)$hidden);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * stories.togglePeerStoriesHidden#bd0415c4 = Bool.
     */
    public function togglePeerStoriesHidden(mixed $peer, bool $hidden): mixed
    {
        $b = Builder::ctor(0xBD0415C4);
        $b->rawBlob($this->peerBlob($peer));
        $b->bool((bool)$hidden);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * stories.togglePinned#9a75a1ef = Vector<int>.
     */
    public function togglePinned(mixed $peer, array $id, bool $pinned): mixed
    {
        $b = Builder::ctor(0x9A75A1EF);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        $b->bool((bool)$pinned);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<int>');
    }

    /**
     * stories.togglePinnedToTop#b297e9b = Bool.
     */
    public function togglePinnedToTop(mixed $peer, array $id): mixed
    {
        $b = Builder::ctor(0xB297E9B);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * stories.updateAlbum#5e5259b6 = StoryAlbum.
     */
    public function updateAlbum(mixed $peer, int $album_id, ?string $title = null, ?array $delete_stories = null, ?array $add_stories = null, ?array $order = null): mixed
    {
        $flags = 0;
        if ($title !== null) { $flags |= (1 << 0); }
        if ($delete_stories !== null) { $flags |= (1 << 1); }
        if ($add_stories !== null) { $flags |= (1 << 2); }
        if ($order !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x5E5259B6);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$album_id);
        if ($title !== null) { $b->string((string)$title); }
        if ($delete_stories !== null) { $b->vectorInt($delete_stories); }
        if ($add_stories !== null) { $b->vectorInt($add_stories); }
        if ($order !== null) { $b->vectorInt($order); }
        return Deserializer::parse($this->client->rpc($b->build()), 'StoryAlbum');
    }
}
