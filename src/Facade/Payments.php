<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every payments.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Payments extends Group
{

    /**
     * payments.applyGiftCode#f6e26854 = Updates.
     */
    public function applyGiftCode(string $slug): mixed
    {
        $b = Builder::ctor(0xF6E26854);
        $b->string((string)$slug);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * payments.assignAppStoreTransaction#80ed747d = Updates.
     */
    public function assignAppStoreTransaction(string $receipt, string $purpose): mixed
    {
        $b = Builder::ctor(0x80ED747D);
        $b->string((string)$receipt);
        $b->rawBlob($purpose);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * payments.assignPlayMarketTransaction#dffd50d3 = Updates.
     */
    public function assignPlayMarketTransaction(string $receipt, string $purpose): mixed
    {
        $b = Builder::ctor(0xDFFD50D3);
        $b->rawBlob($receipt);
        $b->rawBlob($purpose);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * payments.botCancelStarsSubscription#6dfa0622 = Bool.
     */
    public function botCancelStarsSubscription(mixed $user_id, string $charge_id, bool $restore = false): mixed
    {
        $flags = 0;
        if ($restore) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x6DFA0622);
        $b->int($flags);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->string((string)$charge_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * payments.canPurchaseStore#4fdc5ea7 = Bool.
     */
    public function canPurchaseStore(string $purpose): mixed
    {
        $b = Builder::ctor(0x4FDC5EA7);
        $b->rawBlob($purpose);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * payments.changeStarsSubscription#c7770878 = Bool.
     */
    public function changeStarsSubscription(mixed $peer, string $subscription_id, ?bool $canceled = null): mixed
    {
        $flags = 0;
        if ($canceled !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xC7770878);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$subscription_id);
        if ($canceled !== null) { $b->bool((bool)$canceled); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * payments.checkCanSendGift#c0c4edc9 = payments.CheckCanSendGiftResult.
     */
    public function checkCanSendGift(int $gift_id): mixed
    {
        $b = Builder::ctor(0xC0C4EDC9);
        $b->long((int)$gift_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.CheckCanSendGiftResult');
    }

    /**
     * payments.checkGiftCode#8e51b4c1 = payments.CheckedGiftCode.
     */
    public function checkGiftCode(string $slug): mixed
    {
        $b = Builder::ctor(0x8E51B4C1);
        $b->string((string)$slug);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.CheckedGiftCode');
    }

    /**
     * payments.clearSavedInfo#d83d70c1 = Bool.
     */
    public function clearSavedInfo(bool $credentials = false, bool $info = false): mixed
    {
        $flags = 0;
        if ($credentials) { $flags |= (1 << 0); }
        if ($info) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xD83D70C1);
        $b->int($flags);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * payments.connectStarRefBot#7ed5348a = payments.ConnectedStarRefBots.
     */
    public function connectStarRefBot(mixed $peer, mixed $bot): mixed
    {
        $b = Builder::ctor(0x7ED5348A);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peers()->resolveUser($bot));
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.ConnectedStarRefBots');
    }

    /**
     * payments.convertStarGift#74bf076b = Bool.
     */
    public function convertStarGift(string $stargift): mixed
    {
        $b = Builder::ctor(0x74BF076B);
        $b->rawBlob($stargift);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * payments.craftStarGift#b0f9684f = Updates.
     */
    public function craftStarGift(array $stargift): mixed
    {
        $b = Builder::ctor(0xB0F9684F);
        $b->vector($stargift);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * payments.createStarGiftCollection#1f4a0e87 = StarGiftCollection.
     */
    public function createStarGiftCollection(mixed $peer, string $title, array $stargift): mixed
    {
        $b = Builder::ctor(0x1F4A0E87);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$title);
        $b->vector($stargift);
        return Deserializer::parse($this->client->rpc($b->build()), 'StarGiftCollection');
    }

    /**
     * payments.deleteStarGiftCollection#ad5648e8 = Bool.
     */
    public function deleteStarGiftCollection(mixed $peer, int $collection_id): mixed
    {
        $b = Builder::ctor(0xAD5648E8);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$collection_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * payments.editConnectedStarRefBot#e4fca4a3 = payments.ConnectedStarRefBots.
     */
    public function editConnectedStarRefBot(mixed $peer, string $link, bool $revoked = false): mixed
    {
        $flags = 0;
        if ($revoked) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xE4FCA4A3);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$link);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.ConnectedStarRefBots');
    }

    /**
     * payments.exportInvoice#f91b065 = payments.ExportedInvoice.
     */
    public function exportInvoice(string $invoice_media): mixed
    {
        $b = Builder::ctor(0xF91B065);
        $b->rawBlob($invoice_media);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.ExportedInvoice');
    }

    /**
     * payments.fulfillStarsSubscription#cc5bebb3 = Bool.
     */
    public function fulfillStarsSubscription(mixed $peer, string $subscription_id): mixed
    {
        $b = Builder::ctor(0xCC5BEBB3);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$subscription_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * payments.getBankCardData#2e79d779 = payments.BankCardData.
     */
    public function getBankCardData(string $number): mixed
    {
        $b = Builder::ctor(0x2E79D779);
        $b->string((string)$number);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.BankCardData');
    }

    /**
     * payments.getConnectedStarRefBot#b7d998f0 = payments.ConnectedStarRefBots.
     */
    public function getConnectedStarRefBot(mixed $peer, mixed $bot): mixed
    {
        $b = Builder::ctor(0xB7D998F0);
        $b->rawBlob($this->peerBlob($peer));
        $b->rawBlob($this->peers()->resolveUser($bot));
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.ConnectedStarRefBots');
    }

    /**
     * payments.getConnectedStarRefBots#5869a553 = payments.ConnectedStarRefBots.
     */
    public function getConnectedStarRefBots(mixed $peer, int $limit, ?int $offset_date = null, ?string $offset_link = null): mixed
    {
        $flags = 0;
        if ($offset_date !== null) { $flags |= (1 << 2); }
        if ($offset_link !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x5869A553);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($offset_date !== null) { $b->int((int)$offset_date); }
        if ($offset_link !== null) { $b->string((string)$offset_link); }
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.ConnectedStarRefBots');
    }

    /**
     * payments.getCraftStarGifts#fd05dd00 = payments.SavedStarGifts.
     */
    public function getCraftStarGifts(int $gift_id, string $offset, int $limit): mixed
    {
        $b = Builder::ctor(0xFD05DD00);
        $b->long((int)$gift_id);
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.SavedStarGifts');
    }

    /**
     * payments.getGiveawayInfo#f4239425 = payments.GiveawayInfo.
     */
    public function getGiveawayInfo(mixed $peer, int $msg_id): mixed
    {
        $b = Builder::ctor(0xF4239425);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.GiveawayInfo');
    }

    /**
     * payments.getPaymentForm#37148dbb = payments.PaymentForm.
     */
    public function getPaymentForm(string $invoice, ?string $theme_params = null): mixed
    {
        $flags = 0;
        if ($theme_params !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x37148DBB);
        $b->int($flags);
        $b->rawBlob($invoice);
        if ($theme_params !== null) { $b->rawBlob($theme_params); }
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.PaymentForm');
    }

    /**
     * payments.getPaymentReceipt#2478d1cc = payments.PaymentReceipt.
     */
    public function getPaymentReceipt(mixed $peer, int $msg_id): mixed
    {
        $b = Builder::ctor(0x2478D1CC);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.PaymentReceipt');
    }

    /**
     * payments.getPremiumGiftCodeOptions#2757ba54 = Vector<PremiumGiftCodeOption>.
     */
    public function getPremiumGiftCodeOptions(mixed $boost_peer = null): mixed
    {
        $flags = 0;
        if ($boost_peer !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x2757BA54);
        $b->int($flags);
        if ($boost_peer !== null) { $b->rawBlob($this->peerBlob($boost_peer)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<PremiumGiftCodeOption>');
    }

    /**
     * payments.getResaleStarGifts#7a5fa236 = payments.ResaleStarGifts.
     */
    public function getResaleStarGifts(int $gift_id, string $offset, int $limit, bool $sort_by_price = false, bool $sort_by_num = false, bool $for_craft = false, bool $stars_only = false, ?int $attributes_hash = null, ?array $attributes = null): mixed
    {
        $flags = 0;
        if ($sort_by_price) { $flags |= (1 << 1); }
        if ($sort_by_num) { $flags |= (1 << 2); }
        if ($for_craft) { $flags |= (1 << 4); }
        if ($stars_only) { $flags |= (1 << 5); }
        if ($attributes_hash !== null) { $flags |= (1 << 0); }
        if ($attributes !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x7A5FA236);
        $b->int($flags);
        if ($attributes_hash !== null) { $b->long((int)$attributes_hash); }
        $b->long((int)$gift_id);
        if ($attributes !== null) { $b->vector($attributes); }
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.ResaleStarGifts');
    }

    /**
     * payments.getSavedInfo#227d824b = payments.SavedInfo.
     */
    public function getSavedInfo(): mixed
    {
        $b = Builder::ctor(0x227D824B);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.SavedInfo');
    }

    /**
     * payments.getSavedStarGift#b455a106 = payments.SavedStarGifts.
     */
    public function getSavedStarGift(array $stargift): mixed
    {
        $b = Builder::ctor(0xB455A106);
        $b->vector($stargift);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.SavedStarGifts');
    }

    /**
     * payments.getSavedStarGifts#a319e569 = payments.SavedStarGifts.
     */
    public function getSavedStarGifts(mixed $peer, string $offset, int $limit, bool $exclude_unsaved = false, bool $exclude_saved = false, bool $exclude_unlimited = false, bool $exclude_unique = false, bool $sort_by_value = false, bool $exclude_upgradable = false, bool $exclude_unupgradable = false, bool $peer_color_available = false, bool $exclude_hosted = false, ?int $collection_id = null): mixed
    {
        $flags = 0;
        if ($exclude_unsaved) { $flags |= (1 << 0); }
        if ($exclude_saved) { $flags |= (1 << 1); }
        if ($exclude_unlimited) { $flags |= (1 << 2); }
        if ($exclude_unique) { $flags |= (1 << 4); }
        if ($sort_by_value) { $flags |= (1 << 5); }
        if ($exclude_upgradable) { $flags |= (1 << 7); }
        if ($exclude_unupgradable) { $flags |= (1 << 8); }
        if ($peer_color_available) { $flags |= (1 << 9); }
        if ($exclude_hosted) { $flags |= (1 << 10); }
        if ($collection_id !== null) { $flags |= (1 << 6); }
        $b = Builder::ctor(0xA319E569);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($collection_id !== null) { $b->int((int)$collection_id); }
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.SavedStarGifts');
    }

    /**
     * payments.getStarGiftActiveAuctions#a5d0514d = payments.StarGiftActiveAuctions.
     */
    public function getStarGiftActiveAuctions(int $hash): mixed
    {
        $b = Builder::ctor(0xA5D0514D);
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarGiftActiveAuctions');
    }

    /**
     * payments.getStarGiftAuctionAcquiredGifts#6ba2cbec = payments.StarGiftAuctionAcquiredGifts.
     */
    public function getStarGiftAuctionAcquiredGifts(int $gift_id): mixed
    {
        $b = Builder::ctor(0x6BA2CBEC);
        $b->long((int)$gift_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarGiftAuctionAcquiredGifts');
    }

    /**
     * payments.getStarGiftAuctionState#5c9ff4d6 = payments.StarGiftAuctionState.
     */
    public function getStarGiftAuctionState(string $auction, int $version): mixed
    {
        $b = Builder::ctor(0x5C9FF4D6);
        $b->rawBlob($auction);
        $b->int((int)$version);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarGiftAuctionState');
    }

    /**
     * payments.getStarGiftCollections#981b91dd = payments.StarGiftCollections.
     */
    public function getStarGiftCollections(mixed $peer, int $hash): mixed
    {
        $b = Builder::ctor(0x981B91DD);
        $b->rawBlob($this->peerBlob($peer));
        $b->long((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarGiftCollections');
    }

    /**
     * payments.getStarGiftUpgradeAttributes#6d038b58 = payments.StarGiftUpgradeAttributes.
     */
    public function getStarGiftUpgradeAttributes(int $gift_id): mixed
    {
        $b = Builder::ctor(0x6D038B58);
        $b->long((int)$gift_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarGiftUpgradeAttributes');
    }

    /**
     * payments.getStarGiftUpgradePreview#9c9abcb1 = payments.StarGiftUpgradePreview.
     */
    public function getStarGiftUpgradePreview(int $gift_id): mixed
    {
        $b = Builder::ctor(0x9C9ABCB1);
        $b->long((int)$gift_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarGiftUpgradePreview');
    }

    /**
     * payments.getStarGiftWithdrawalUrl#d06e93a8 = payments.StarGiftWithdrawalUrl.
     */
    public function getStarGiftWithdrawalUrl(string $stargift, string $password): mixed
    {
        $b = Builder::ctor(0xD06E93A8);
        $b->rawBlob($stargift);
        $b->rawBlob($password);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarGiftWithdrawalUrl');
    }

    /**
     * payments.getStarGifts#c4563590 = payments.StarGifts.
     */
    public function getStarGifts(int $hash): mixed
    {
        $b = Builder::ctor(0xC4563590);
        $b->int((int)$hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarGifts');
    }

    /**
     * payments.getStarsGiftOptions#d3c96bc8 = Vector<StarsGiftOption>.
     */
    public function getStarsGiftOptions(mixed $user_id = null): mixed
    {
        $flags = 0;
        if ($user_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xD3C96BC8);
        $b->int($flags);
        if ($user_id !== null) { $b->rawBlob($this->peers()->resolveUser($user_id)); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<StarsGiftOption>');
    }

    /**
     * payments.getStarsGiveawayOptions#bd1efd3e = Vector<StarsGiveawayOption>.
     */
    public function getStarsGiveawayOptions(): mixed
    {
        $b = Builder::ctor(0xBD1EFD3E);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<StarsGiveawayOption>');
    }

    /**
     * payments.getStarsRevenueAdsAccountUrl#d1d7efc5 = payments.StarsRevenueAdsAccountUrl.
     */
    public function getStarsRevenueAdsAccountUrl(mixed $peer): mixed
    {
        $b = Builder::ctor(0xD1D7EFC5);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarsRevenueAdsAccountUrl');
    }

    /**
     * payments.getStarsRevenueStats#d91ffad6 = payments.StarsRevenueStats.
     */
    public function getStarsRevenueStats(mixed $peer, bool $dark = false, bool $ton = false): mixed
    {
        $flags = 0;
        if ($dark) { $flags |= (1 << 0); }
        if ($ton) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xD91FFAD6);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarsRevenueStats');
    }

    /**
     * payments.getStarsRevenueWithdrawalUrl#2433dc92 = payments.StarsRevenueWithdrawalUrl.
     */
    public function getStarsRevenueWithdrawalUrl(mixed $peer, string $password, bool $ton = false, ?int $amount = null): mixed
    {
        $flags = 0;
        if ($ton) { $flags |= (1 << 0); }
        if ($amount !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x2433DC92);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        if ($amount !== null) { $b->long((int)$amount); }
        $b->rawBlob($password);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarsRevenueWithdrawalUrl');
    }

    /**
     * payments.getStarsStatus#4ea9b3bf = payments.StarsStatus.
     */
    public function getStarsStatus(mixed $peer, bool $ton = false): mixed
    {
        $flags = 0;
        if ($ton) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x4EA9B3BF);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarsStatus');
    }

    /**
     * payments.getStarsSubscriptions#32512c5 = payments.StarsStatus.
     */
    public function getStarsSubscriptions(mixed $peer, string $offset, bool $missing_balance = false): mixed
    {
        $flags = 0;
        if ($missing_balance) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x32512C5);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$offset);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarsStatus');
    }

    /**
     * payments.getStarsTopupOptions#c00ec7d3 = Vector<StarsTopupOption>.
     */
    public function getStarsTopupOptions(): mixed
    {
        $b = Builder::ctor(0xC00EC7D3);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<StarsTopupOption>');
    }

    /**
     * payments.getStarsTransactions#69da4557 = payments.StarsStatus.
     */
    public function getStarsTransactions(mixed $peer, string $offset, int $limit, bool $inbound = false, bool $outbound = false, bool $ascending = false, bool $ton = false, ?string $subscription_id = null): mixed
    {
        $flags = 0;
        if ($inbound) { $flags |= (1 << 0); }
        if ($outbound) { $flags |= (1 << 1); }
        if ($ascending) { $flags |= (1 << 2); }
        if ($ton) { $flags |= (1 << 4); }
        if ($subscription_id !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x69DA4557);
        $b->int($flags);
        if ($subscription_id !== null) { $b->string((string)$subscription_id); }
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarsStatus');
    }

    /**
     * payments.getStarsTransactionsByID#2dca16b8 = payments.StarsStatus.
     */
    public function getStarsTransactionsByID(mixed $peer, array $id, bool $ton = false): mixed
    {
        $flags = 0;
        if ($ton) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x2DCA16B8);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->vector($id);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.StarsStatus');
    }

    /**
     * payments.getSuggestedStarRefBots#d6b48f7 = payments.SuggestedStarRefBots.
     */
    public function getSuggestedStarRefBots(mixed $peer, string $offset, int $limit, bool $order_by_revenue = false, bool $order_by_date = false): mixed
    {
        $flags = 0;
        if ($order_by_revenue) { $flags |= (1 << 0); }
        if ($order_by_date) { $flags |= (1 << 1); }
        $b = Builder::ctor(0xD6B48F7);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$offset);
        $b->int((int)$limit);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.SuggestedStarRefBots');
    }

    /**
     * payments.getUniqueStarGift#a1974d72 = payments.UniqueStarGift.
     */
    public function getUniqueStarGift(string $slug): mixed
    {
        $b = Builder::ctor(0xA1974D72);
        $b->string((string)$slug);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.UniqueStarGift');
    }

    /**
     * payments.getUniqueStarGiftValueInfo#4365af6b = payments.UniqueStarGiftValueInfo.
     */
    public function getUniqueStarGiftValueInfo(string $slug): mixed
    {
        $b = Builder::ctor(0x4365AF6B);
        $b->string((string)$slug);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.UniqueStarGiftValueInfo');
    }

    /**
     * payments.launchPrepaidGiveaway#5ff58f20 = Updates.
     */
    public function launchPrepaidGiveaway(mixed $peer, int $giveaway_id, string $purpose): mixed
    {
        $b = Builder::ctor(0x5FF58F20);
        $b->rawBlob($this->peerBlob($peer));
        $b->long((int)$giveaway_id);
        $b->rawBlob($purpose);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * payments.refundStarsCharge#25ae8f4a = Updates.
     */
    public function refundStarsCharge(mixed $user_id, string $charge_id): mixed
    {
        $b = Builder::ctor(0x25AE8F4A);
        $b->rawBlob($this->peers()->resolveUser($user_id));
        $b->string((string)$charge_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * payments.reorderStarGiftCollections#c32af4cc = Bool.
     */
    public function reorderStarGiftCollections(mixed $peer, array $order): mixed
    {
        $b = Builder::ctor(0xC32AF4CC);
        $b->rawBlob($this->peerBlob($peer));
        $b->vectorInt($order);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * payments.resolveStarGiftOffer#e9ce781c = Updates.
     */
    public function resolveStarGiftOffer(int $offer_msg_id, bool $decline = false): mixed
    {
        $flags = 0;
        if ($decline) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xE9CE781C);
        $b->int($flags);
        $b->int((int)$offer_msg_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * payments.saveStarGift#2a2a697c = Bool.
     */
    public function saveStarGift(string $stargift, bool $unsave = false): mixed
    {
        $flags = 0;
        if ($unsave) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x2A2A697C);
        $b->int($flags);
        $b->rawBlob($stargift);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * payments.sendPaymentForm#2d03522f = payments.PaymentResult.
     */
    public function sendPaymentForm(int $form_id, string $invoice, string $credentials, ?string $requested_info_id = null, ?string $shipping_option_id = null, ?int $tip_amount = null): mixed
    {
        $flags = 0;
        if ($requested_info_id !== null) { $flags |= (1 << 0); }
        if ($shipping_option_id !== null) { $flags |= (1 << 1); }
        if ($tip_amount !== null) { $flags |= (1 << 2); }
        $b = Builder::ctor(0x2D03522F);
        $b->int($flags);
        $b->long((int)$form_id);
        $b->rawBlob($invoice);
        if ($requested_info_id !== null) { $b->string((string)$requested_info_id); }
        if ($shipping_option_id !== null) { $b->string((string)$shipping_option_id); }
        $b->rawBlob($credentials);
        if ($tip_amount !== null) { $b->long((int)$tip_amount); }
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.PaymentResult');
    }

    /**
     * payments.sendStarGiftOffer#8fb86b41 = Updates.
     */
    public function sendStarGiftOffer(mixed $peer, string $slug, string $price, int $duration, int $random_id, ?int $allow_paid_stars = null): mixed
    {
        $flags = 0;
        if ($allow_paid_stars !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x8FB86B41);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->string((string)$slug);
        $b->rawBlob($price);
        $b->int((int)$duration);
        $b->long((int)$random_id);
        if ($allow_paid_stars !== null) { $b->long((int)$allow_paid_stars); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * payments.sendStarsForm#7998c914 = payments.PaymentResult.
     */
    public function sendStarsForm(int $form_id, string $invoice): mixed
    {
        $b = Builder::ctor(0x7998C914);
        $b->long((int)$form_id);
        $b->rawBlob($invoice);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.PaymentResult');
    }

    /**
     * payments.toggleChatStarGiftNotifications#60eaefa1 = Bool.
     */
    public function toggleChatStarGiftNotifications(mixed $peer, bool $enabled = false): mixed
    {
        $flags = 0;
        if ($enabled) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x60EAEFA1);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * payments.toggleStarGiftsPinnedToTop#1513e7b0 = Bool.
     */
    public function toggleStarGiftsPinnedToTop(mixed $peer, array $stargift): mixed
    {
        $b = Builder::ctor(0x1513E7B0);
        $b->rawBlob($this->peerBlob($peer));
        $b->vector($stargift);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * payments.transferStarGift#7f18176a = Updates.
     */
    public function transferStarGift(string $stargift, mixed $to_id): mixed
    {
        $b = Builder::ctor(0x7F18176A);
        $b->rawBlob($stargift);
        $b->rawBlob($this->peerBlob($to_id));
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * payments.updateStarGiftCollection#4fddbee7 = StarGiftCollection.
     */
    public function updateStarGiftCollection(mixed $peer, int $collection_id, ?string $title = null, ?array $delete_stargift = null, ?array $add_stargift = null, ?array $order = null): mixed
    {
        $flags = 0;
        if ($title !== null) { $flags |= (1 << 0); }
        if ($delete_stargift !== null) { $flags |= (1 << 1); }
        if ($add_stargift !== null) { $flags |= (1 << 2); }
        if ($order !== null) { $flags |= (1 << 3); }
        $b = Builder::ctor(0x4FDDBEE7);
        $b->int($flags);
        $b->rawBlob($this->peerBlob($peer));
        $b->int((int)$collection_id);
        if ($title !== null) { $b->string((string)$title); }
        if ($delete_stargift !== null) { $b->vector($delete_stargift); }
        if ($add_stargift !== null) { $b->vector($add_stargift); }
        if ($order !== null) { $b->vector($order); }
        return Deserializer::parse($this->client->rpc($b->build()), 'StarGiftCollection');
    }

    /**
     * payments.updateStarGiftPrice#edbe6ccb = Updates.
     */
    public function updateStarGiftPrice(string $stargift, string $resell_amount): mixed
    {
        $b = Builder::ctor(0xEDBE6CCB);
        $b->rawBlob($stargift);
        $b->rawBlob($resell_amount);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * payments.upgradeStarGift#aed6e4f5 = Updates.
     */
    public function upgradeStarGift(string $stargift, bool $keep_original_details = false): mixed
    {
        $flags = 0;
        if ($keep_original_details) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xAED6E4F5);
        $b->int($flags);
        $b->rawBlob($stargift);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }

    /**
     * payments.validateRequestedInfo#b6c8f12b = payments.ValidatedRequestedInfo.
     */
    public function validateRequestedInfo(string $invoice, string $info, bool $save = false): mixed
    {
        $flags = 0;
        if ($save) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xB6C8F12B);
        $b->int($flags);
        $b->rawBlob($invoice);
        $b->rawBlob($info);
        return Deserializer::parse($this->client->rpc($b->build()), 'payments.ValidatedRequestedInfo');
    }
}
