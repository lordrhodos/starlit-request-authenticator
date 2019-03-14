<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac;

use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterInterface;
use Starlit\Request\Authenticator\Hmac\Transformer\HmacDataTransformerInterface;
use Starlit\Request\Authenticator\Hmac\Transformer\RequestHmacDataTransformer;

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
     * data string in the format "%method% %uri%\n%content%". Inject your own transformer if you want to support
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

    public function generateHmacForRequest(RequestAdapterInterface $request): string
    {
        $data = $this->hmacDataTransformer->getDataForRequest($request);

        return $this->generateHmac($data);
    }
}
