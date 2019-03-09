<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac;

use PHPUnit\Framework\TestCase;
use Starlit\Request\Authenticator\Hmac\HmacGenerator;
use Symfony\Component\HttpFoundation\Request;

class HmacGeneratorTest extends TestCase
{
    /**
     * @var HmacGenerator
     */
    private $generator;

    protected function setUp()
    {
        $this->generator = new HmacGenerator();
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::getDataStringFromRequest
     */
    public function testGetDataStringFromRequest(): void
    {
        $request = Request::create('/foo', Request::METHOD_GET, [], [], [], [], 'bar');
        $data = $this->generator->getDataStringFromRequest($request);
        $this->assertIsString($data);
        $expectedString = \sprintf("%s %s\n%s", Request::METHOD_GET, 'http://localhost/foo', 'bar');
        $this->assertSame($expectedString, $data);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmac
     */
    public function testGenerateHmac(): void
    {
        $hmac = $this->generator->generateHmac('my secret', 'data');
        $this->assertIsString($hmac);
        $this->assertSame('cdff956a85a68a697f4a23677d02eaa2cffdebc0d68b86c5c2801ec86eb10200', $hmac);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmac
     */
    public function testGenerateHmacWithEmptySecretWillThrowException(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Parameters missing for MAC generation');
        $this->generator->generateHmac('', 'data');
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmac
     */
    public function testGenerateHmacWithEmptyDataWillThrowException(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Parameters missing for MAC generation');
        $this->generator->generateHmac('my secret', '');
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmac
     */
    public function testGenerateHmacWithUnknownHashingAlgorithm(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('hashing algorithm \'foo\' is not supported');
        $this->generator->generateHmac('my secret', 'data', 'foo');
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmacForRequest
     */
    public function testGenerateHmacForRequest(): void
    {
        $request = Request::create('/foo');
        $hmac = $this->generator->generateHmacForRequest('my secret', $request);
        $this->assertIsString($hmac);
        $this->assertSame('c38d090572c79b214a7165da2dec4be9cdd8acf607bbb950dba2ca5a24073358', $hmac);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmacForRequest
     * @covers \Starlit\Request\Authenticator\Hmac\HmacGenerator::generateHmac
     */
    public function testGenerateHmacForRequestWithEmptySecretWillThrowException(): void
    {
        $request = Request::create('/foo');
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Parameters missing for MAC generation');
        $this->generator->generateHmacForRequest('', $request);
    }
}
