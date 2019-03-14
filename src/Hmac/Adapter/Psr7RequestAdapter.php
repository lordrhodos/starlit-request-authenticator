<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac\Adapter;

use Psr\Http\Message\RequestInterface;

class Psr7RequestAdapter implements RequestAdapterInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    public function getUri(): string
    {
        return $this->request->getUri()->__toString();
    }

    public function getContent(): string
    {
        return $this->request->getBody()->__toString();
    }

    public function getHeader(string $key): ?string
    {
        $header = $this->request->getHeaderLine($key);

        return !empty($header) ? $header : null;
    }
}
