<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Tests\Hmac;

use PHPUnit\Framework\TestCase;
use Starlit\Request\Authenticator\AuthenticatorInterface;
use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterFactory;
use Starlit\Request\Authenticator\Hmac\HmacAuthenticator;
use Starlit\Request\Authenticator\Hmac\HmacGenerator;
use Symfony\Component\HttpFoundation\Request;

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
    public function testAuthenticateSymfonyRequest(): void
    {
        $request = Request::create('/foo');
        $request->headers->add(['MAC' => '1ade58546c1bf2cec5b80cf75e48719a28d5e542d4582b62790d4827366826cc']);
        $this->assertTrue($this->authenticator->authenticateRequest($request));
    }

    /**
     * @covers \Starlit\Request\Authenticator\Hmac\HmacAuthenticator::authenticateRequest()
     */
    public function testAuthenticateRequestWithMissingMacHeader(): void
    {
        $request = Request::create('/foo');
        $this->assertFalse($this->authenticator->authenticateRequest($request));
    }
}
