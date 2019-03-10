<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac\DataTransformer;

use GuzzleHttp\Stream\Stream;
use PHPUnit\Framework\TestCase;
use Starlit\Request\Authenticator\Hmac\DataTransformer\HmacDataTransformerInterface;
use Starlit\Request\Authenticator\Hmac\DataTransformer\RequestHmacDataTransformer;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Message\Request as Guzzle5Request;


class RequestHmacDataTransformerTest extends TestCase
{
    /**
     * @var RequestHmacDataTransformer
     */
    private $transformer;

    protected function setUp()
    {
        $this->transformer = new RequestHmacDataTransformer();
    }

    public function testConstruction()
    {
        $this->assertInstanceOf(HmacDataTransformerInterface::class, $this->transformer);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\DataTransformer\RequestHmacDataTransformer::getDataForRequest()
     * @covers \Starlit\Request\Authenticator\Hmac\DataTransformer\RequestHmacDataTransformer::getData()
     * @covers \Starlit\Request\Authenticator\Hmac\DataTransformer\RequestHmacDataTransformer::getUriFromSymfonyRequest()
     * @dataProvider provideValidRequests
     */
    public function testGetDataForRequestWithValidRequestTypes($request): void
    {
        $data = $this->transformer->getDataForRequest($request);
        $this->assertSame("GET http://foobar.test/foo?param=value\nbar", $data);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\DataTransformer\RequestHmacDataTransformer::getDataForRequest()
     * @dataProvider provideInvalidRequests
     */
    public function testGetDataForRequestWithInvalidRequestTypes($request): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Request type not supported. Only PSR-7, Symfony or Guzzle5 requests are supported.'
        );
        $this->transformer->getDataForRequest($request);
    }

    public function provideValidRequests(): array
    {
        $uri = 'http://foobar.test/foo?param=value';
        $psr7Request = new Psr7Request('GET', $uri, [], 'bar');
        $symfonyRequest = SymfonyRequest::create($uri, SymfonyRequest::METHOD_GET, [], [], [], [], 'bar');
        $guzzle5Request = new Guzzle5Request('GET', $uri, [], Stream::factory('bar'));

        return [
            [$psr7Request],
            [$symfonyRequest],
            [$guzzle5Request]
        ];
    }

    public function provideInvalidRequests(): array
    {
        return [
            ['http://foobar.test/foo'],
            [''],
            [true],
            [123]
        ];
    }
}
