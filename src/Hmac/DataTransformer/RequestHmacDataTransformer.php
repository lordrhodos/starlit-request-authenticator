<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac\DataTransformer;

use Psr\Http\Message\RequestInterface as Psr7Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use GuzzleHttp\Message\Request as Guzzle5Request;

class RequestHmacDataTransformer implements HmacDataTransformerInterface
{
    public function getDataForRequest($request): string
    {
        if ($request instanceof Psr7Request) {
            $uri = $request->getUri()->__toString();
            $content = $request->getBody()->__toString();
        } elseif ($request instanceof SymfonyRequest) {
            $uri = $this->getUriFromSymfonyRequest($request);
            $content = $request->getContent();
        } elseif ($request instanceof Guzzle5Request) {
            $uri = $request->getUrl();
            $content = $request->getBody()->__toString();
        } else {
            throw new \InvalidArgumentException(
                'Request type not supported. Only PSR-7, Symfony or Guzzle5 requests are supported.'
            );
        }

        $method = $request->getMethod();

        return $this->getData($method, $uri, $content);
    }

    private function getData(string $method, string $uri, string $content): string
    {
        return \sprintf("%s %s\n%s", $method, rtrim($uri, '/'), $content);
    }

    /**
     * helper method to build a uri without reordering url parameters in a query string alphabetically
     * which the interal Request::getUri function does (called normalize)
     *
     * @see SymfonyRequest::getUri()
     * @see SymfonyRequest::getQueryString()
     * @see SymfonyRequest::normalizeQueryString()
     */
    private function getUriFromSymfonyRequest(SymfonyRequest $request): string
    {
        $qs = $request->server->get('QUERY_STRING');
        if (!empty($qs)) {
            $qs = '?' . $qs;
        }

        return $request->getSchemeAndHttpHost() . $request->getBaseUrl() . $request->getPathInfo().$qs;
    }
}
