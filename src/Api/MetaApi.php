<?php

declare(strict_types=1);

namespace Bpt\Api;

use Bpt\Session\EncryptedSession;
use Bpt\TL\Builder;

/**
 * Handwritten account.* / updates.* / help.* methods.
 */
final class MetaApi
{
    public function __construct(
        private readonly EncryptedSession $session,
        private readonly int $apiId,
        private readonly int $layer,
    ) {
    }

    /** account.updateProfile#78515775 flags + optional fields. */
    public function updateProfile(?string $firstName = null, ?string $lastName = null, ?string $about = null): string
    {
        $flags = 0;
        if ($firstName !== null) {
            $flags |= 1;
        }
        if ($lastName !== null) {
            $flags |= 2;
        }
        if ($about !== null) {
            $flags |= 4;
        }
        $b = Builder::ctor(0x78515775)->int($flags);
        if ($firstName !== null) {
            $b->string($firstName);
        }
        if ($lastName !== null) {
            $b->string($lastName);
        }
        if ($about !== null) {
            $b->string($about);
        }
        return $this->session->callWithLayer($b->build(), $this->apiId, $this->layer);
    }

    /** updates.getState#edd4882a = updates.State. */
    public function getState(): string
    {
        return $this->session->callWithLayer(Builder::ctor(0xEDD4882A)->build(), $this->apiId, $this->layer);
    }

    /** help.getConfig#c4f9186b = Config. */
    public function getConfig(): string
    {
        return $this->session->callWithLayer(Builder::ctor(0xC4F9186B)->build(), $this->apiId, $this->layer);
    }

    /** updates.getState#edd4882a is above; trigger flush without parsing. */
    public function ping(): string
    {
        // ping#7abe77ec ping_id:long = Pong. Cheap keep-alive that also
        // flushes piggybacked updates into the session queue.
        $body = Builder::ctor(0x7ABE77EC)->long(random_int(1, PHP_INT_MAX))->build();
        return $this->session->call($body);
    }

    /** account.updateStatus#6628562c offline:Bool = Bool. */
    public function setOnline(bool $online = true): string
    {
        // Online => offline=false; offline => offline=true.
        $b = Builder::ctor(0x6628562C)->bool(!$online);
        return $this->session->callWithLayer($b->build(), $this->apiId, $this->layer);
    }

    /**
     * updates.getDifference#19c2f763 flags:# pts:int date:int qts:int.
     * flags=0 subset (no total limits).
     */
    public function getDifference(int $pts, int $date, int $qts): string
    {
        $body = Builder::ctor(0x19C2F763)->int(0)->int($pts)->int($date)->int($qts)->build();
        return $this->session->callWithLayer($body, $this->apiId, $this->layer);
    }
}
