<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac\Adapter;

use Symfony\Component\HttpFoundation\Request;

class SymfonyRequestAdapter implements RequestAdapterInterface
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    /**
     * helper method to build a uri without reordering url parameters in a query string alphabetically
     * which the interal Request::getUri function does (called normalize)
     *
     * @see SymfonyRequest::getUri()
     * @see SymfonyRequest::getQueryString()
     * @see SymfonyRequest::normalizeQueryString()
     */
    public function getUri(): string
    {
        $queryString = $this->request->server->get('QUERY_STRING');
        if (!empty($queryString)) {
            $queryString = '?' . $queryString;
        }

        return $this->request->getSchemeAndHttpHost()
            . $this->request->getBaseUrl() . $this->request->getPathInfo() . $queryString;
    }

    public function getContent(): string
    {
        return $this->request->getContent();
    }

    public function getHeader(string $key): ?string
    {
        return $this->request->headers->get($key);
    }
}
