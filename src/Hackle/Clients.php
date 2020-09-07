<?php


namespace Hackle;


use Hackle\Internal\Client\ProxyClient;
use Hackle\Internal\HackleSdk;
use Hackle\Internal\Http\Guzzle;
use Monolog\Logger;

final class Clients
{
    /**
     * @param string $sdkKey
     * @param array $config
     *
     * @return ClientInterface
     */
    public static function proxy($sdkKey, $config = array())
    {
        $logger = new Logger("Hackle");
        $sdk = new HackleSdk($sdkKey, "php-sdk", Version::CURRENT);

        $baseUri = isset($config['base_uri']) ? $config['base_uri'] : 'http://localhost:8888';
        $httpClient = Guzzle::client($baseUri, $sdk);

        return new ProxyClient($logger, $httpClient);
    }
}