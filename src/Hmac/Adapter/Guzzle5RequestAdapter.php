<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac\Adapter;

use GuzzleHttp\Message\Request;

class Guzzle5RequestAdapter implements RequestAdapterInterface
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    public function getUri(): string
    {
        return $this->request->getUrl();
    }

    public function getContent(): string
    {
        $body = $this->request->getBody();

        return isset($body) ? $body->__toString() : '';
    }

    public function getHeader(string $key): ?string
    {
        $header = $this->request->getHeader($key);

        return !empty($header) ? $header : null;
    }
}
