<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Mocks;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Psr7Request implements RequestInterface
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $version;

    public function __construct(
        string $method,
        string $uri,
        array $headers = [],
        string $body = '',
        string $version = '1.1')
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body;
        $this->version = $version;
    }

    public function getRequestTarget()
    {
        // TODO: Implement getRequestTarget() method.
    }

    public function withRequestTarget($requestTarget)
    {
        // TODO: Implement withRequestTarget() method.
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        // TODO: Implement withMethod() method.
    }

    public function getUri(): UriInterface
    {
        return new Psr7Uri($this->uri);
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        // TODO: Implement withUri() method.
    }

    public function getProtocolVersion()
    {
        return $this->version;
    }

    public function withProtocolVersion($version)
    {
        // TODO: Implement withProtocolVersion() method.
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasHeader($name)
    {
        // TODO: Implement hasHeader() method.
    }

    public function getHeader($name)
    {
        // TODO: Implement getHeader() method.
    }

    public function getHeaderLine($name)
    {
        // TODO: Implement getHeaderLine() method.
    }

    public function withHeader($name, $value)
    {
        // TODO: Implement withHeader() method.
    }

    public function withAddedHeader($name, $value)
    {
        // TODO: Implement withAddedHeader() method.
    }

    public function withoutHeader($name)
    {
        // TODO: Implement withoutHeader() method.
    }

    public function getBody()
    {
        return new Psr7Stream($this->body);
    }

    public function withBody(StreamInterface $body)
    {
        // TODO: Implement withBody() method.
    }
}
