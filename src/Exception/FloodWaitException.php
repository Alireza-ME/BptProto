<?php

declare(strict_types=1);

namespace Bpt\Exception;

use Bpt\Session\RpcErrorException;

/**
 * FLOOD_WAIT_x as a typed exception (still catchable as RpcErrorException).
 */
final class FloodWaitException extends RpcErrorException
{
    public function __construct(
        public readonly int $seconds,
        string $rpcType = 'FLOOD_WAIT',
    ) {
        parent::__construct(420, $rpcType);
    }

    public static function fromRpc(RpcErrorException $e): ?self
    {
        if (preg_match('/^FLOOD_WAIT_(\d+)$/', $e->rpcType, $m)) {
            return new self((int)$m[1], $e->rpcType);
        }
        if ($e->rpcType === 'FLOOD_TEST_PHONE_WAIT') {
            return new self(0, $e->rpcType);
        }
        return null;
    }
}
