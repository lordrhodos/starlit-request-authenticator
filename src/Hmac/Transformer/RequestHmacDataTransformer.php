<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac\Transformer;

use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterInterface;

class RequestHmacDataTransformer implements HmacDataTransformerInterface
{
    public function getDataForRequest(RequestAdapterInterface $request): string
    {
        return $this->getData($request->getMethod(), $request->getUri(), $request->getContent());
    }

    private function getData(string $method, string $uri, string $content): string
    {
        return \sprintf("%s %s\n%s", $method, rtrim($uri, '/'), $content);
    }
}
