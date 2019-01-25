<?php declare(strict_types=1);

namespace Starweb\Authenticator\Tests\Hmac;

use PHPUnit\Framework\TestCase;
use Starweb\Authenticator\AuthenticatorInterface;
use Starweb\Authenticator\Hmac\HmacAuthenticator;
use Starweb\Authenticator\Hmac\HmacGenerator;
use Symfony\Component\HttpFoundation\Request;

class HmacAuthenticatorTest extends TestCase
{
    /**
     * @var HmacAuthenticator
     */
    private $authenticator;

    protected function setUp()
    {
        $generator = new HmacGenerator();
        $this->authenticator = new HmacAuthenticator('my secret key', $generator);
    }


    /**
     * @covers \Starweb\Authenticator\Hmac\HmacAuthenticator::__construct()
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(AuthenticatorInterface::class, $this->authenticator);
    }

    /**
     * @covers \Starweb\Authenticator\Hmac\HmacAuthenticator::__construct()
     */
    public function testConstructorWithEmptyStringAsSecretThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid secret key');

        $generator = new HmacGenerator();
        new HmacAuthenticator('', $generator);
    }

    /**
     * @covers \Starweb\Authenticator\Hmac\HmacAuthenticator::authenticateRequest()
     */
    public function testAuthenticateRequest()
    {
        $request = Request::create('/foo');
        $request->headers->add(['MAC' => '1ade58546c1bf2cec5b80cf75e48719a28d5e542d4582b62790d4827366826cc']);
        $this->assertTrue($this->authenticator->authenticateRequest($request));
    }

    /**
     * @covers \Starweb\Authenticator\Hmac\HmacAuthenticator::authenticateRequest()
     */
    public function testAuthenticateRequestWithMissingMacHeader()
    {
        $request = Request::create('/foo');
        $this->assertFalse($this->authenticator->authenticateRequest($request));
    }
}
