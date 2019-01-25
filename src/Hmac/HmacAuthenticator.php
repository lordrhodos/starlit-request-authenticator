<?php declare(strict_types=1);

namespace Starweb\Authenticator\Hmac;

use Starweb\Authenticator\AuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HmacAuthenticator implements AuthenticatorInterface
{
    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var HmacGenerator
     */
    private $generator;

    public function __construct(string $secretKey, HmacGenerator $generator)
    {
        if (!$secretKey) {
            throw new \InvalidArgumentException('invalid secret key');
        }

        $this->secretKey = $secretKey;
        $this->generator = $generator;
    }

    public function authenticateRequest(Request $request): bool
    {
        $receivedMac = $request->headers->get('MAC');
        if (!$receivedMac) {
            return false;
        }

        $generatedMac = $this->generator->generateHmacForRequest($this->secretKey, $request);

        return ($receivedMac === $generatedMac);
    }
}
