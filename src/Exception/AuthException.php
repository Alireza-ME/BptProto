<?php

declare(strict_types=1);

namespace Bpt\Exception;

use Bpt\Session\RpcErrorException;

/**
 * Auth/session errors: AUTH_KEY_*, SESSION_*, USER_DEACTIVATED...
 * Still catchable as RpcErrorException for BC.
 */
final class AuthException extends RpcErrorException
{
    public static function isAuthError(string $rpcType): bool
    {
        return str_starts_with($rpcType, 'AUTH_KEY_')
            || str_starts_with($rpcType, 'SESSION_')
            || str_starts_with($rpcType, 'USER_')
            || $rpcType === 'AUTH_RESTART'
            || $rpcType === 'PHONE_CODE_INVALID'
            || $rpcType === 'PHONE_CODE_EXPIRED';
    }

    public static function fromRpc(RpcErrorException $e): ?self
    {
        return self::isAuthError($e->rpcType) ? new self($e->rpcCode, $e->rpcType) : null;
    }
}
