<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac\Adapter;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Starlit\Request\Authenticator\Hmac\Adapter\Guzzle5RequestAdapter;
use Starlit\Request\Authenticator\Hmac\Adapter\Psr7RequestAdapter;
use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterFactory;
use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterInterface;
use Starlit\Request\Authenticator\Hmac\Adapter\SymfonyRequestAdapter;

class RequestAdapterFactoryTest extends TestCase
{
    /**
     * @var RequestAdapterFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new RequestAdapterFactory();
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterFactory::create
     */
    public function testCreatePsr7Request()
    {
        $psr7RequestMock = $this->createMock(RequestInterface::class);
        $request = $this->factory->create($psr7RequestMock);

        $this->assertInstanceOf(RequestAdapterInterface::class, $request);
        $this->assertInstanceOf(Psr7RequestAdapter::class, $request);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterFactory::create
     */
    public function testCreateSymfonyRequest()
    {
        if (class_exists('\Symfony\Component\HttpFoundation\Request')) {

            $symfonyRequest = new \Symfony\Component\HttpFoundation\Request();
            $request = $this->factory->create($symfonyRequest);

            $this->assertInstanceOf(RequestAdapterInterface::class, $request);
            $this->assertInstanceOf(SymfonyRequestAdapter::class, $request);
        } else {
            $this->assertTrue(true, 'symfony/http-foundation package not loaded');
        }
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterFactory::create
     */
    public function testCreateGuzzle5Request()
    {
        if (class_exists('\GuzzleHttp\Message\Request')) {
            $psr7Request = new \GuzzleHttp\Message\Request('GET', '/foo');
            $request = $this->factory->create($psr7Request);

            $this->assertInstanceOf(RequestAdapterInterface::class, $request);
            $this->assertInstanceOf(Guzzle5RequestAdapter::class, $request);
        } else {
            $this->assertTrue(true, 'guzzlehttp/guzzle version 5 package not loaded');
        }
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterFactory::create
     */
    public function testCreateNotSupportedRequestTypeThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Request type not supported. Only PSR-7, Symfony or Guzzle5 requests are supported.'
        );

        $this->factory->create('foo');
    }
}
