<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac\Adapter;

interface RequestAdapterFactoryInterface
{
    public function create($request): RequestAdapterInterface;
}