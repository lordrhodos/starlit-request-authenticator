<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac\Transformer;

use GuzzleHttp\Stream\Stream;
use PHPUnit\Framework\TestCase;
use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterFactory;
use Starlit\Request\Authenticator\Hmac\Transformer\HmacDataTransformerInterface;
use Starlit\Request\Authenticator\Hmac\Transformer\RequestHmacDataTransformer;
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
     * @covers \Starlit\Request\Authenticator\Hmac\Transformer\RequestHmacDataTransformer::getDataForRequest()
     * @covers \Starlit\Request\Authenticator\Hmac\Transformer\RequestHmacDataTransformer::getData()
     * @dataProvider provideValidRequests
     */
    public function testGetDataForRequestWithValidRequestTypes($request): void
    {
        $factory = new RequestAdapterFactory();
        $data = $this->transformer->getDataForRequest($factory->create($request));
        $this->assertSame("GET http://foobar.test/foo?param=value\nbar", $data);
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
}
