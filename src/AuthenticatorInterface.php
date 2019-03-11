<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator;

interface AuthenticatorInterface
{
    public function authenticateRequest($request): bool;
}
