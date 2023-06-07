<?php

namespace Hackle;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hackle\Internal\Client\HackleClientImpl;
use Hackle\Internal\Core\HackleCore;
use Hackle\Internal\Event\Dispatcher\EventDispatcher;
use Hackle\Internal\Event\Processor\DefaultUserEventProcessor;
use Hackle\Internal\Http\HackleMiddleware;
use Hackle\Internal\Http\SdkCacheMiddleware;
use Hackle\Internal\Http\SdkHeaderMiddleware;
use Hackle\Internal\Time\SystemClock;
use Hackle\Internal\User\InternalHackleUserResolver;
use Hackle\Internal\Workspace\HttpWorkspaceFetcher;
use Hackle\Internal\Workspace\Sdk;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class HackleClients
{
    public static function create(string $sdkKey, ?HackleConfig $config): HackleClient
    {
        if ($config === null) {
            $config = HackleConfig::getDefault();
        }

        $sdk = new Sdk($sdkKey);
        $logger = new Logger("Hackle", [new ErrorLogHandler()]);
        $workspaceFetcherHttpClient = self::createWorkspaceFetcherHttpClient($sdk, $logger);
        $workspaceFetcher = new HttpWorkspaceFetcher($config->getSdkUri(), $workspaceFetcherHttpClient, $logger);
        $eventDispatcherHttpClient = self::createEventDispatcherHttpClient($sdk);
        $eventDispatcher = new EventDispatcher($config->getEventUri(), $eventDispatcherHttpClient, $logger);
        $eventProcessor = new DefaultUserEventProcessor($eventDispatcher, 100, $logger);
        $core = HackleCore::create($workspaceFetcher, $eventProcessor);

        return new HackleClientImpl($core, new InternalHackleUserResolver(), $logger);
    }

    private static function createWorkspaceFetcherHttpClient(Sdk $sdk, LoggerInterface $logger): Client
    {
        $stack = HandlerStack::create();
        $middlewares = array(
            new SdkCacheMiddleware("/tmp/hackle/", 10, $logger),
            new SdkHeaderMiddleware($sdk, new SystemClock())
        );
        foreach ($middlewares as $middleware) {
            self::applyMiddleware($stack, $middleware);
        }
        $configs = ["timeout" => 10, "connect_timeout" => 5, "handler" => $stack];
        return new Client($configs);
    }

    private static function createEventDispatcherHttpClient(Sdk $sdk): Client
    {
        $stack = HandlerStack::create();
        $headerMiddleware = new SdkHeaderMiddleware($sdk, new SystemClock());
        $headerMiddleware->process($stack);
        $configs = ["timeout" => 10, "connect_timeout" => 5, "handler" => $stack];
        return new Client($configs);
    }

    private static function applyMiddleware(HandlerStack $stack, HackleMiddleware $hackleMiddleware)
    {
        $hackleMiddleware->process($stack);
    }
}
