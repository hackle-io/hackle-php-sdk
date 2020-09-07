<?php


namespace Hackle\Internal\Http;


use GuzzleHttp\Client;
use Hackle\Internal\HackleSdk;

final class Guzzle
{
    /**
     * @param $baseUri string
     * @param $sdk HackleSdk
     * @return Client
     */
    public static function client($baseUri, $sdk)
    {
        return new Client([
            'base_uri' => $baseUri,
            'headers' => [
                'X-HACKLE-SDK-KEY' => $sdk->getKey(),
                'X-HACKLE-SDK-NAME' => $sdk->getName(),
                'X-HACKLE-SDK-VERSION' => $sdk->getVersion()
            ]
        ]);
    }

    /**
     * @param int $statusCode
     * @return bool
     */
    public static function isSuccessful($statusCode) {
        return $statusCode >= 200 && $statusCode < 300;
    }
}