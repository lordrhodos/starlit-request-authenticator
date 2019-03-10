<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac\DataTransformer;

interface HmacDataTransformerInterface
{
    public function getDataForRequest($request): string;
}
