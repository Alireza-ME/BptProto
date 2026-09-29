<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every smsjobs.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Smsjobs extends Group
{

    /**
     * smsjobs.finishJob#4f1ebf24 = Bool.
     */
    public function finishJob(string $job_id, ?string $error = null): mixed
    {
        $flags = 0;
        if ($error !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x4F1EBF24);
        $b->int($flags);
        $b->string((string)$job_id);
        if ($error !== null) { $b->string((string)$error); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * smsjobs.getSmsJob#778d902f = SmsJob.
     */
    public function getSmsJob(string $job_id): mixed
    {
        $b = Builder::ctor(0x778D902F);
        $b->string((string)$job_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'SmsJob');
    }

    /**
     * smsjobs.getStatus#10a698e8 = smsjobs.Status.
     */
    public function getStatus(): mixed
    {
        $b = Builder::ctor(0x10A698E8);
        return Deserializer::parse($this->client->rpc($b->build()), 'smsjobs.Status');
    }

    /**
     * smsjobs.isEligibleToJoin#edc39d0 = smsjobs.EligibilityToJoin.
     */
    public function isEligibleToJoin(): mixed
    {
        $b = Builder::ctor(0xEDC39D0);
        return Deserializer::parse($this->client->rpc($b->build()), 'smsjobs.EligibilityToJoin');
    }

    /**
     * smsjobs.join#a74ece2d = Bool.
     */
    public function join(): mixed
    {
        $b = Builder::ctor(0xA74ECE2D);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * smsjobs.leave#9898ad73 = Bool.
     */
    public function leave(): mixed
    {
        $b = Builder::ctor(0x9898AD73);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * smsjobs.updateSettings#93fa0bf = Bool.
     */
    public function updateSettings(bool $allow_international = false): mixed
    {
        $flags = 0;
        if ($allow_international) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x93FA0BF);
        $b->int($flags);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }
}
