<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac\Adapter;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Psr\Http\Message\RequestInterface as Psr7Request;
use GuzzleHttp\Message\Request as Guzzle5Request;

class RequestAdapterFactory implements RequestAdapterFactoryInterface
{
    public function create($request): RequestAdapterInterface
    {
        if ($request instanceof Psr7Request) {
            return new Psr7RequestAdapter($request);
        } elseif ($request instanceof SymfonyRequest) {
            return new SymfonyRequestAdapter($request);
        } elseif ($request instanceof Guzzle5Request) {
            return new Guzzle5RequestAdapter($request);
        } else {
            throw new \InvalidArgumentException(
                'Request type not supported. Only PSR-7, Symfony or Guzzle5 requests are supported.'
            );
        }
    }
}
