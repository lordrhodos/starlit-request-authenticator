<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac;

use PHPUnit\Framework\TestCase;
use Starlit\Request\Authenticator\Hmac\HmacGenerator;
use Starlit\Request\Authenticator\Hmac\DataTransformer\RequestHmacDataTransformer;
use Symfony\Component\HttpFoundation\Request;

class HmacGeneratorTest extends TestCase
{
    /**
     * @var HmacGenerator
     */
    private $generator;

    protected function setUp()
    {
        $this->generator = new HmacGenerator('my secret');
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::__construct()
     */
    public function testConstruction()
    {
        $reflectionClass = new \ReflectionClass($this->generator);
        $hmacDataTransformerProperty = $reflectionClass->getProperty('hmacDataTransformer');
        $hmacDataTransformerProperty->setAccessible(true);
        $hmacDataTransformer = $hmacDataTransformerProperty->getValue($this->generator);
        $this->assertInstanceOf(RequestHmacDataTransformer::class, $hmacDataTransformer);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::__construct()
     */
    public function testConstructionWithHmacDataTransformer()
    {
        $hmacDataTransformerMock = $this->createMock(RequestHmacDataTransformer::class);
        $generator = new HmacGenerator('my secret', $hmacDataTransformerMock);
        $reflectionClass = new \ReflectionClass($this->generator);
        $hmacDataTransformerProperty = $reflectionClass->getProperty('hmacDataTransformer');
        $hmacDataTransformerProperty->setAccessible(true);
        $hmacDataTransformer = $hmacDataTransformerProperty->getValue($generator);
        $this->assertSame($hmacDataTransformer, $hmacDataTransformerMock);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::__construct()
     */
    public function testConstructionWithEmptyStringAsSecretThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The secret key can not be empty.');

        new HmacGenerator('');
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmac
     */
    public function testGenerateHmac(): void
    {
        $hmac = $this->generator->generateHmac('data');
        $this->assertIsString($hmac);
        $this->assertSame('cdff956a85a68a697f4a23677d02eaa2cffdebc0d68b86c5c2801ec86eb10200', $hmac);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmac
     */
    public function testGenerateHmacWithEmptyDataWillThrowException(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Data can not be empty.');
        $this->generator->generateHmac('');
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmac
     */
    public function testGenerateHmacWithUnknownHashingAlgorithm(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('hashing algorithm [foo] is not supported');
        $this->generator->generateHmac('data', 'foo');
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmacForRequest
     */
    public function testGenerateHmacForRequest(): void
    {
        $request = Request::create('/foo');
        $hmac = $this->generator->generateHmacForRequest($request);
        $this->assertIsString($hmac);
        $this->assertSame('c38d090572c79b214a7165da2dec4be9cdd8acf607bbb950dba2ca5a24073358', $hmac);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmacForRequest
     */
    public function testGenerateHmacForRequestCallsGetDataForRequestOnHmacDataTransformer(): void
    {
        $hmacDataTransformerMock = $this->createMock(RequestHmacDataTransformer::class);
        $hmacDataTransformerMock
            ->expects($this->once())
            ->method('getDataForRequest')
            ->willReturn("GET http://localhost/foo\n");

        $generator = new HmacGenerator('my secret', $hmacDataTransformerMock);
        $request = Request::create('/foo');
        $hmac = $generator->generateHmacForRequest($request);
        $this->assertIsString($hmac);
        $this->assertSame('c38d090572c79b214a7165da2dec4be9cdd8acf607bbb950dba2ca5a24073358', $hmac);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmacForRequest
     */
    public function testGenerateHmacForRequestWithInvalidRequestWillThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Request type not supported. Only PSR-7, Symfony or Guzzle5 requests are supported'
        );
        $this->generator->generateHmacForRequest('/foo');
    }
}