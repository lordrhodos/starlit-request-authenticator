<?php declare(strict_types=1);

namespace Starweb\Authenticator\Hmac;

use Symfony\Component\HttpFoundation\Request;

class HmacGenerator
{
    public function generateHmac(string $secret, string $data, string $hashingAlgorithm = 'sha256'): string
    {
        if (!$secret || !$data) {
            throw new \InvalidArgumentException('Parameters missing for MAC generation');
        }

        // See https://secure.php.net/manual/en/function.hash-hmac-algos.php for a list of supported algorithms
        if (!($hmac = @\hash_hmac($hashingAlgorithm, $data, $secret))) {
            throw new \LogicException("hashing algorithm '$hashingAlgorithm' is not supported'");
        }

        return $hmac;
    }

    public function getDataStringFromRequest(Request $request): string
    {
        return \sprintf("%s %s\n%s", $request->getMethod(), \rtrim($request->getUri(), '/'), $request->getContent());
    }

    public function generateHmacForRequest(string $secret, Request $request): string
    {
        $data = $this->getDataStringFromRequest($request);

        return $this->generateHmac($secret, $data);
    }
}
