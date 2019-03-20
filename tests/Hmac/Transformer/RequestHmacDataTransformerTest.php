<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac\Transformer;

use GuzzleHttp\Stream\Stream;
use PHPUnit\Framework\TestCase;
use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterFactory;
use Starlit\Request\Authenticator\Hmac\Transformer\HmacDataTransformerInterface;
use Starlit\Request\Authenticator\Hmac\Transformer\RequestHmacDataTransformer;
use Nyholm\Psr7\Request as Psr7Request;

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
        $validRequests = [[$psr7Request]];

        if (class_exists('\Symfony\Component\HttpFoundation\Request')) {
            $symfonyRequest = \Symfony\Component\HttpFoundation\Request::create(
                $uri,
                \Symfony\Component\HttpFoundation\Request::METHOD_GET,
                [],
                [],
                [],
                [],
                'bar'
            );
            $validRequests[] = [$symfonyRequest];
        }

        if (class_exists('\GuzzleHttp\Message\Request')) {
            $guzzle5Request = new \GuzzleHttp\Message\Request('GET', $uri, [], Stream::factory('bar'));
            $validRequests[] = [$guzzle5Request];
        }

        return $validRequests;
    }
}
