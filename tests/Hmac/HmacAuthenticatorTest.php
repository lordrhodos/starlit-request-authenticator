<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac;

use Nyholm\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Starlit\Request\Authenticator\AuthenticatorInterface;
use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterFactory;
use Starlit\Request\Authenticator\Hmac\HmacAuthenticator;
use Starlit\Request\Authenticator\Hmac\HmacGenerator;

class HmacAuthenticatorTest extends TestCase
{
    /**
     * @var HmacAuthenticator
     */
    private $authenticator;

    protected function setUp()
    {
        $generator = new HmacGenerator('my secret key');
        $factory = new RequestAdapterFactory();
        $this->authenticator = new HmacAuthenticator($generator, $factory);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacAuthenticator::__construct()
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(AuthenticatorInterface::class, $this->authenticator);
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacAuthenticator::authenticateRequest()
     */
    public function testAuthenticateRequest(): void
    {
        $request = new Request('GET', 'http://localhost/foo');
        $request = $request->withHeader('MAC', '1ade58546c1bf2cec5b80cf75e48719a28d5e542d4582b62790d4827366826cc');
        $this->assertTrue($this->authenticator->authenticateRequest($request));
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacAuthenticator::authenticateRequest()
     */
    public function testAuthenticateRequestWithMissingMacHeader(): void
    {
        $request = new Request('GET', 'http://localhost/foo');
        $this->assertFalse($this->authenticator->authenticateRequest($request));
    }
}
