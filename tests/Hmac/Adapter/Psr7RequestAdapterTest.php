<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac\Adapter;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
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
        $psr7Request = new Request('GET', $uri);
        $request = new Psr7RequestAdapter($psr7Request);

        $this->assertInstanceOf(RequestAdapterInterface::class, $request);
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($uri, $request->getUri());
        $this->assertSame('', $request->getContent());
        $this->assertNull($request->getHeader('MAC'));
    }
}
