<?php

namespace Hackle\Internal\Http;

use Psr\Http\Message\ResponseInterface;

class Https
{
    public const LAST_MODIFIED = "Last-Modified";
    public const IF_MODIFIED_SINCE = "If-Modified-Since";

    public static function isSuccessful(ResponseInterface $response): bool
    {
        $statusCode = $response->getStatusCode();
        return $statusCode >= 200 && $statusCode < 300;
    }

    public static function isNotModified(ResponseInterface $response): bool
    {
        return $response->getStatusCode() === 304;
    }

    public static function lastModified(ResponseInterface $response): ?string
    {
        if ($response->hasHeader(self::LAST_MODIFIED)) {
            return $response->getHeader(self::LAST_MODIFIED)[0];
        }

        return null;
    }
}
