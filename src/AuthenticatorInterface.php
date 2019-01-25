<?php declare(strict_types=1);

namespace Starweb\Authenticator;

use Symfony\Component\HttpFoundation\Request;

interface AuthenticatorInterface
{
    public function authenticateRequest(Request $request): bool;
}
