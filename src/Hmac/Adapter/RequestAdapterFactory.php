<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac\Adapter;

use Psr\Http\Message\RequestInterface as Psr7Request;

class RequestAdapterFactory implements RequestAdapterFactoryInterface
{
    public function create($request): RequestAdapterInterface
    {
        if ($request instanceof Psr7Request) {
            return new Psr7RequestAdapter($request);
        } elseif (class_exists('\Symfony\Component\HttpFoundation\Request')
            && $request instanceof \Symfony\Component\HttpFoundation\Request)
        {
            return new SymfonyRequestAdapter($request);
        } elseif (class_exists('\GuzzleHttp\Message\Request')
            && $request instanceof \GuzzleHttp\Message\Request) {
                return new Guzzle5RequestAdapter($request);
        } else {
            throw new \InvalidArgumentException(
                'Request type not supported. Only PSR-7, Symfony or Guzzle5 requests are supported.'
            );
        }
    }
}
