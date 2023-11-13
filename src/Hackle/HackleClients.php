<?php

namespace Hackle;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hackle\Internal\Client\HackleClientImpl;
use Hackle\Internal\Core\HackleCore;
use Hackle\Internal\Event\Dispatcher\EventDispatcher;
use Hackle\Internal\Event\Processor\DefaultUserEventProcessor;
use Hackle\Internal\Http\SdkHeaderMiddleware;
use Hackle\Internal\Logger\Log;
use Hackle\Internal\Model\Sdk;
use Hackle\Internal\Repository\FileRepository;
use Hackle\Internal\Time\SystemClock;
use Hackle\Internal\User\InternalHackleUserResolver;
use Hackle\Internal\Workspace\Sync\HttpWorkspaceSynchronizer;
use Hackle\Internal\Workspace\Sync\SynchronizeWorkspaceFetcher;
use Hackle\Internal\Workspace\Sync\WorkspaceRepository;
use Hackle\Internal\Workspace\Sync\WorkspaceSynchronizeManager;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

final class HackleClients
{
    public static function create(string $sdkKey, ?HackleConfig $config = null): HackleClient
    {
        if ($config === null) {
            $config = HackleConfig::getDefault();
        }

        $logger = self::createLogger();
        $clock = new SystemClock();
        $sdk = new Sdk($sdkKey);
        $httpClient = self::createHttpClient($sdk);

        $repository = new FileRepository("/tmp/hackle/");
        $workspaceRepository = new WorkspaceRepository($repository, $sdk);
        $httpWorkspaceSynchronizer = new HttpWorkspaceSynchronizer(
            $config->getSdkUri(),
            $sdk,
            $httpClient,
            $workspaceRepository
        );
        $workspaceSynchronizeManager = new WorkspaceSynchronizeManager(
            $clock,
            $workspaceRepository,
            $httpWorkspaceSynchronizer
        );
        $workspaceFetcher = new SynchronizeWorkspaceFetcher($clock, $workspaceSynchronizeManager, 10000);

        $eventDispatcher = new EventDispatcher($config->getEventUri(), $httpClient, $logger);
        $eventProcessor = new DefaultUserEventProcessor($eventDispatcher, 100, $logger);
        $core = HackleCore::create($workspaceFetcher, $eventProcessor);

        return new HackleClientImpl($core, new InternalHackleUserResolver(), $logger);
    }

    private static function createHttpClient(Sdk $sdk): Client
    {
        $stack = HandlerStack::create();
        $stack->push(new SdkHeaderMiddleware($sdk, new SystemClock()));
        $configs = ["timeout" => 10, "connect_timeout" => 5, "handler" => $stack];
        return new Client($configs);
    }

    private static function createLogger(): Logger
    {
        $logger = new Logger("Hackle", [new ErrorLogHandler()]);
        Log::initialize($logger);
        return $logger;
    }
}
