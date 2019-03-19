<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac\Adapter;

use PHPUnit\Framework\TestCase;
use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterInterface;
use Starlit\Request\Authenticator\Hmac\Adapter\SymfonyRequestAdapter;

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
        if (class_exists('Symfony\Component\HttpFoundation\Request')) {
            $uri = 'http://foo.test/bar?paramB=b&paramA=a';
            $symfonyRequest = \Symfony\Component\HttpFoundation\Request::create($uri);
            $request = new SymfonyRequestAdapter($symfonyRequest);

            $this->assertInstanceOf(RequestAdapterInterface::class, $request);
            $this->assertSame('GET', $request->getMethod());
            $this->assertSame($uri, $request->getUri());
            $this->assertSame('', $request->getContent());
            $this->assertNull($request->getHeader('MAC'));
        } else {
            $this->assertTrue(true, 'symfony/http-foundation library not loaded');
        }
    }
}
