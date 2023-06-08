<?php

namespace Hackle\Internal\Utils;

use Psr\Http\Message\ResponseInterface;

class Https
{
    public static function isSuccessful(ResponseInterface $response): bool
    {
        $statusCode = $response->getStatusCode();
        return $statusCode >= 200 && $statusCode < 300;
    }
}
