<?php declare(strict_types=1);

namespace Starlit\Request\Authenticator\Hmac\Adapter;

interface RequestAdapterInterface
{
    public function getMethod(): string;

    public function getUri(): string;

    public function getContent(): string;

    public function getHeader(string $key): ?string;
}
