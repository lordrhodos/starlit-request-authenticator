<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac;

use Starlit\Request\Authenticator\AuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HmacAuthenticator implements AuthenticatorInterface
{
    /**
     * @var HmacGenerator
     */
    private $generator;

    public function __construct(HmacGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function authenticateRequest($request): bool
    {
        $receivedMac = $request->headers->get('MAC');
        if (!$receivedMac) {
            return false;
        }

        $generatedMac = $this->generator->generateHmacForRequest($request);

        return ($receivedMac === $generatedMac);
    }
}
