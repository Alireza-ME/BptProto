<?php

declare(strict_types=1);

namespace Bpt\Session;

/**
 * RPC-level error returned inside rpc_result.
 *
 * Subclassed by typed errors (FloodWaitException, AuthException) which
 * remain catchable as RpcErrorException.
 */
class RpcErrorException extends \RuntimeException
{
    /**
     * @param int    $rpcCode e.g. 303, 400, 401.
     * @param string $rpcType e.g. PHONE_MIGRATE_4, SESSION_PASSWORD_NEEDED.
     */
    public function __construct(
        public readonly int $rpcCode,
        public readonly string $rpcType,
    ) {
        parent::__construct("RPC_ERROR {$rpcCode}: {$rpcType}");
    }
}
