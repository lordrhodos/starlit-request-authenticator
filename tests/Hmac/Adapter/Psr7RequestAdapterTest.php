<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac\Adapter;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Starlit\Request\Authenticator\Hmac\Adapter\Psr7RequestAdapter;
use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterInterface;

class Psr7RequestAdapterTest extends TestCase
{
    /**
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\Psr7RequestAdapter::__construct
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\Psr7RequestAdapter::getMethod
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\Psr7RequestAdapter::getUri
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\Psr7RequestAdapter::getContent
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\Psr7RequestAdapter::getHeader
     */
    public function testAdaption()
    {
        $uri = 'http://foo.test/bar?paramB=b&paramA=a';

        $psr7UriMock = $this->createMock(UriInterface::class);
        $psr7UriMock
            ->expects($this->once())
            ->method('__toString')
            ->willReturn($uri);

        $psr7StreamMock = $this->createMock(StreamInterface::class);
        $psr7StreamMock
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('');

        $psr7RequestMock = $this->createMock(RequestInterface::class);
        $psr7RequestMock
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $psr7RequestMock
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($psr7UriMock);

        $psr7RequestMock
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($psr7StreamMock);

        $request = new Psr7RequestAdapter($psr7RequestMock);

        $this->assertInstanceOf(RequestAdapterInterface::class, $request);
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($uri, $request->getUri());
        $this->assertSame('', $request->getContent());
        $this->assertNull($request->getHeader('MAC'));
    }
}
