<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every help.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Help extends Group
{

    /**
     * help.acceptTermsOfService#ee72f79a = Bool.
     */
    public function acceptTermsOfService(string $id): mixed
    {
        $b = Builder::ctor(0xEE72F79A);
        $b->rawBlob($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * help.dismissSuggestion#f50dbaa1 = Bool.
     */
    public function dismissSuggestion(mixed $peer, string $suggestion): mixed
    {
        $b = Builder::ctor(0xF50DBAA1);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$suggestion);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * help.editUserInfo#66b91b70 = help.UserInfo.
     */
    public function editUserInfo(mixed $user_id, string $message, array $entities): mixed
    {
        $b = Builder::ctor(0x66B91B70);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->string((string)$message);
        $b->vector($entities);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.UserInfo');
    }

    /**
     * help.getAppConfig#61e3f854 = help.AppConfig.
     */
    public function getAppConfig(int $hash): mixed
    {
        $b = Builder::ctor(0x61E3F854);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.AppConfig');
    }

    /**
     * help.getAppUpdate#522d5a7d = help.AppUpdate.
     */
    public function getAppUpdate(string $source): mixed
    {
        $b = Builder::ctor(0x522D5A7D);
        $b->string((string)$source);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.AppUpdate');
    }

    /**
     * help.getCdnConfig#52029342 = CdnConfig.
     */
    public function getCdnConfig(): mixed
    {
        $b = Builder::ctor(0x52029342);
        return Deserializer::parse($this->client->rpc($b->build()), 'CdnConfig');
    }

    /**
     * help.getConfig#c4f9186b = Config.
     */
    public function getConfig(): mixed
    {
        $b = Builder::ctor(0xC4F9186B);
        return Deserializer::parse($this->client->rpc($b->build()), 'Config');
    }

    /**
     * help.getCountriesList#735787a8 = help.CountriesList.
     */
    public function getCountriesList(string $lang_code, int $hash): mixed
    {
        $b = Builder::ctor(0x735787A8);
        $b->string((string)$lang_code);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.CountriesList');
    }

    /**
     * help.getDeepLinkInfo#3fedc75f = help.DeepLinkInfo.
     */
    public function getDeepLinkInfo(string $path): mixed
    {
        $b = Builder::ctor(0x3FEDC75F);
        $b->string((string)$path);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.DeepLinkInfo');
    }

    /**
     * help.getInviteText#4d392343 = help.InviteText.
     */
    public function getInviteText(): mixed
    {
        $b = Builder::ctor(0x4D392343);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.InviteText');
    }

    /**
     * help.getNearestDc#1fb33026 = NearestDc.
     */
    public function getNearestDc(): mixed
    {
        $b = Builder::ctor(0x1FB33026);
        return Deserializer::parse($this->client->rpc($b->build()), 'NearestDc');
    }

    /**
     * help.getPassportConfig#c661ad08 = help.PassportConfig.
     */
    public function getPassportConfig(int $hash): mixed
    {
        $b = Builder::ctor(0xC661AD08);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.PassportConfig');
    }

    /**
     * help.getPeerColors#da80f42f = help.PeerColors.
     */
    public function getPeerColors(int $hash): mixed
    {
        $b = Builder::ctor(0xDA80F42F);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.PeerColors');
    }

    /**
     * help.getPeerProfileColors#abcfa9fd = help.PeerColors.
     */
    public function getPeerProfileColors(int $hash): mixed
    {
        $b = Builder::ctor(0xABCFA9FD);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.PeerColors');
    }

    /**
     * help.getPremiumPromo#b81b93d4 = help.PremiumPromo.
     */
    public function getPremiumPromo(): mixed
    {
        $b = Builder::ctor(0xB81B93D4);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.PremiumPromo');
    }

    /**
     * help.getPromoData#c0977421 = help.PromoData.
     */
    public function getPromoData(): mixed
    {
        $b = Builder::ctor(0xC0977421);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.PromoData');
    }

    /**
     * help.getRecentMeUrls#3dc0f114 = help.RecentMeUrls.
     */
    public function getRecentMeUrls(string $referer): mixed
    {
        $b = Builder::ctor(0x3DC0F114);
        $b->string((string)$referer);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.RecentMeUrls');
    }

    /**
     * help.getSupport#9cdf08cd = help.Support.
     */
    public function getSupport(): mixed
    {
        $b = Builder::ctor(0x9CDF08CD);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.Support');
    }

    /**
     * help.getSupportName#d360e72c = help.SupportName.
     */
    public function getSupportName(): mixed
    {
        $b = Builder::ctor(0xD360E72C);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.SupportName');
    }

    /**
     * help.getTermsOfServiceUpdate#2ca51fd1 = help.TermsOfServiceUpdate.
     */
    public function getTermsOfServiceUpdate(): mixed
    {
        $b = Builder::ctor(0x2CA51FD1);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.TermsOfServiceUpdate');
    }

    /**
     * help.getTimezonesList#49b30240 = help.TimezonesList.
     */
    public function getTimezonesList(int $hash): mixed
    {
        $b = Builder::ctor(0x49B30240);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'help.TimezonesList');
    }

    /**
     * help.getUserInfo#38a08d3 = help.UserInfo.
     */
    public function getUserInfo(mixed $user_id): mixed
    {
        $b = Builder::ctor(0x38A08D3);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'help.UserInfo');
    }

    /**
     * help.hidePromoData#1e251c95 = Bool.
     */
    public function hidePromoData(mixed $peer): mixed
    {
        $b = Builder::ctor(0x1E251C95);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * help.saveAppLog#6f02f748 = Bool.
     */
    public function saveAppLog(array $events): mixed
    {
        $b = Builder::ctor(0x6F02F748);
        $b->vector($events);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * help.setBotUpdatesStatus#ec22cfcd = Bool.
     */
    public function setBotUpdatesStatus(int $pending_updates_count, string $message): mixed
    {
        $b = Builder::ctor(0xEC22CFCD);
        $b->int((int)$pending_updates_count);
        $b->string((string)$message);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }
}
