<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac\Adapter;

use PHPUnit\Framework\TestCase;
use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterInterface;
use Starlit\Request\Authenticator\Hmac\Adapter\SymfonyRequestAdapter;
use Symfony\Component\HttpFoundation\Request;

class SymfonyRequestAdapterTest extends TestCase
{
    /**
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\SymfonyRequestAdapter::__construct()
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\SymfonyRequestAdapter::getMethod
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\SymfonyRequestAdapter::getUri
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\SymfonyRequestAdapter::getContent
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\SymfonyRequestAdapter::getHeader
     */
    public function testAdaption()
    {
        $uri = 'http://foo.test/bar?paramB=b&paramA=a';
        $symfonyRequest = Request::create($uri);
        $request = new SymfonyRequestAdapter($symfonyRequest);

        $this->assertInstanceOf(RequestAdapterInterface::class, $request);
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($uri, $request->getUri());
        $this->assertSame('', $request->getContent());
        $this->assertNull($request->getHeader('MAC'));
    }
}
