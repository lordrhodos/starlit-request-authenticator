<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac\Adapter;

use GuzzleHttp\Message\Request;
use PHPUnit\Framework\TestCase;
use Starlit\Request\Authenticator\Hmac\Adapter\Guzzle5RequestAdapter;
use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterInterface;

class Guzzle5RequestAdapterTest extends TestCase
{
    /**
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\Guzzle5RequestAdapter::__construct()
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\Guzzle5RequestAdapter::getMethod
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\Guzzle5RequestAdapter::getUri
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\Guzzle5RequestAdapter::getContent
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\Guzzle5RequestAdapter::getHeader
     */
    public function testAdaption()
    {
        $uri = 'http://foo.test/bar?paramB=b&paramA=a';
        $guzzle5Request = new Request('GET', $uri);
        $request = new Guzzle5RequestAdapter($guzzle5Request);

        $this->assertInstanceOf(RequestAdapterInterface::class, $request);
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($uri, $request->getUri());
        $this->assertSame('', $request->getContent());
        $this->assertNull($request->getHeader('MAC'));
    }
}
