<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac\Transformer;

use Starlit\Request\Authenticator\Hmac\Adapter\RequestAdapterInterface;

interface HmacDataTransformerInterface
{
    public function getDataForRequest(RequestAdapterInterface $request): string;
}
