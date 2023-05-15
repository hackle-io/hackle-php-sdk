<?php

namespace Hackle;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Hackle\Internal\Client\ProxyClient;
use Hackle\Internal\HackleSdk;
use Hackle\Internal\Http\Guzzle;
use Hackle\Internal\Http\HackleMiddleware;
use Hackle\Internal\Http\SdkCacheMiddleware;
use Hackle\Internal\Http\SdkHeaderMiddleware;
use Hackle\Internal\Workspace\Sdk;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class HackleClients
{
    //public static function create(string $sdkKey): HackleClient
    //{
    //    return self::create($sdkKey, HackleConfig::getDefault());
    //}
    //
    //public static function create(string $sdkKey, HackleConfig $config): HackleClient
    //{
    //    $sdk = Sdk::load($sdkKey);
    //
    //
    //}
    private function createHttpClient(Sdk $sdk, LoggerInterface $logger): Client
    {
        $stack = HandlerStack::create();
        $middlewares = array(new SdkHeaderMiddleware($sdk), new SdkCacheMiddleware("/tmp/hackle/", 10, $logger),);
        foreach ($middlewares as $middleware) {
            $this->applyMiddleware($stack, $middleware);
        }
        $configs = [
            "timeout" => 10,
            "connect_timeout" => 5,
            "handler" => $stack
        ];
        return new Client($configs);
    }

    private function applyMiddleware(HandlerStack $stack, HackleMiddleware $hackleMiddleware)
    {
        $hackleMiddleware->process($stack);
    }
}
