<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac;

use Starlit\Request\Authenticator\AuthenticatorInterface;
use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterFactoryInterface;

class HmacAuthenticator implements AuthenticatorInterface
{
    /**
     * @var HmacGenerator
     */
    private $generator;

    /**
     * @var RequestAdapterFactoryInterface
     */
    private $requestAdapterFactory;

    public function __construct(HmacGenerator $generator, RequestAdapterFactoryInterface $requestAdapterFactory)
    {
        $this->generator = $generator;
        $this->requestAdapterFactory = $requestAdapterFactory;
    }

    public function authenticateRequest($request): bool
    {
        $request = $this->requestAdapterFactory->create($request);
        $receivedMac = $request->getHeader('MAC');
        if (!$receivedMac) {
            return false;
        }

        $generatedMac = $this->generator->generateHmacForRequest($request);

        return ($receivedMac === $generatedMac);
    }
}
