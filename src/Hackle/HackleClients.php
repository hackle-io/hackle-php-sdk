<?php

namespace Hackle;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hackle\Internal\HackleSdk;
use Hackle\Internal\Http\HackleMiddleware;
use Hackle\Internal\Http\SdkCacheMiddleware;
use Hackle\Internal\Http\SdkHeaderMiddleware;
use Hackle\Internal\Time\SystemClock;
use Hackle\Internal\Workspace\HttpWorkspaceFetcher;
use Hackle\Internal\Workspace\Sdk;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class HackleClients
{
    public static function create(string $sdkKey, ?HackleConfig $config): void
    {
        if ($config == null) {
            $config = HackleConfig::getDefault();
        }
        $sdk = new Sdk($sdkKey);
        $client = self::createHttpClient($sdk, new Logger('HACKLE'));
        $workspaceFetcher = new HttpWorkspaceFetcher($config->getSdkUri(), $client, $config->getLogger());

    }

    private static function createHttpClient(Sdk $sdk, LoggerInterface $logger): Client
    {
        $stack = HandlerStack::create();
        $middlewares = array(new SdkCacheMiddleware("/tmp/hackle/", 30, $logger), new SdkHeaderMiddleware($sdk, new SystemClock()));
        foreach ($middlewares as $middleware) {
            self::applyMiddleware($stack, $middleware);
        }
        $configs = ["timeout" => 10, "connect_timeout" => 5, "handler" => $stack];
        return new Client($configs);
    }

    private static function applyMiddleware(HandlerStack $stack, HackleMiddleware $hackleMiddleware)
    {
        $hackleMiddleware->process($stack);
    }
}
