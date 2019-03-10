<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac;

use Psr\Http\Message\RequestInterface as Psr7Request;
use Starlit\Request\Authenticator\Hmac\DataTransformer\HmacDataTransformerInterface;
use Starlit\Request\Authenticator\Hmac\DataTransformer\RequestHmacDataTransformer;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use GuzzleHttp\Message\Request as Guzzle5Request;

class HmacGenerator
{
    // see https://secure.php.net/manual/en/function.hash-hmac-algos.php
    private const DEFAULT_HASHING_ALGORITHM = 'sha256';

    /**
     * @var string
     */
    private $secretKey;

    /**
     * Transforms a request into a data string used by the hash_mac function. The default transfomer builds the
     * data string in the format "%method% %uri% %content%". Inject your own transformer if you want to support
     * a different data string.
     *
     * @var HmacDataTransformerInterface
     */
    private $hmacDataTransformer;

    public function __construct(string $key, HmacDataTransformerInterface $hmacDataTransformer = null)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('The secret key can not be empty.');
        }

        $this->secretKey = $key;

        if ($hmacDataTransformer === null) {
            $hmacDataTransformer = new RequestHmacDataTransformer();
        }

        $this->hmacDataTransformer = $hmacDataTransformer;
    }

    public function generateHmac(string $data, string $hashingAlgorithm = self::DEFAULT_HASHING_ALGORITHM): string
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Data can not be empty.');
        }

        if (!($hmac = @\hash_hmac($hashingAlgorithm, $data, $this->secretKey))) {
            throw new \LogicException("The hashing algorithm [$hashingAlgorithm] is not supported.");
        }

        return $hmac;
    }

    /**
     * @param string $secret
     * @param Psr7Request|SymfonyRequest|Guzzle5Request $request
     *
     * @return string
     */
    public function generateHmacForRequest($request): string
    {
        if (!$request instanceof Psr7Request
            && !$request instanceof SymfonyRequest
            && !$request instanceof Guzzle5Request
        ) {
            throw new \InvalidArgumentException(
                'Request type not supported. Only PSR-7, Symfony or Guzzle5 requests are supported'
            );
        }

        $data = $this->hmacDataTransformer->getDataForRequest($request);

        return $this->generateHmac($data);
    }
}
