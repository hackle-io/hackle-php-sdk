<?php

namespace Hackle;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\Psr6CacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class HackleConfigBuilder
{
    const DEFAULT_SDK_URI = "https://sdk.hackle.io";
    const DEFAULT_EVENT_URI = "https://event.hackle.io";
    const DEFAULT_MONITORING_URI = "https://monitoring.hackle.io";

    /**@var string */
    private $sdkUri;

    /**@var string */
    private $eventUri;

    /**@var string */
    private $monitoringUri;

    /** @var LoggerInterface */
    private $logger;

    /** @var CacheMiddleware */
    private $cache;

    public function __construct()
    {
        $this->sdkUri = self::DEFAULT_SDK_URI;
        $this->eventUri = self::DEFAULT_EVENT_URI;
        $this->monitoringUri = self::DEFAULT_MONITORING_URI;
        $this->logger = $this->getDefaultLogger();
        $defaultCache = $this->getDefaultCache();
        if (!is_null($defaultCache)) {
            $this->cache = $defaultCache;
        }
    }

    private function getDefaultLogger(): LoggerInterface
    {
        return new Logger("Hackle", [new ErrorLogHandler()]);
    }

    private function getDefaultCache(): ?CacheMiddleware
    {
        if (class_exists('\Kevinrob\GuzzleCache\CacheMiddleware')) {
            $fileSystemAdapter = new Local("/tmp/hackle/");
            $fileSystem = new Filesystem($fileSystemAdapter);
            $cacheStorage = new Psr6CacheStorage(new FilesystemCachePool($fileSystem));
            return new CacheMiddleware(new GreedyCacheStrategy($cacheStorage, 10));
        } else {
            $this->logger->error("Kevinrob\GuzzleCache\CacheMiddleware was not installed");
            return null;
        }
    }

    public function sdkUri(string $sdkUri): self
    {
        $this->sdkUri = rtrim($sdkUri, '/');
        return $this;
    }

    public function eventUri(string $eventUri): self
    {
        $this->eventUri = rtrim($eventUri, '/');
        return $this;
    }

    public function monitoringUri(string $monitoringUri): self
    {
        $this->monitoringUri = rtrim($monitoringUri, '/');
        return $this;
    }

    public function options(array $options = []): self
    {
        if (isset($options['logger'])) {
            $this->logger = $options['logger'];
        }

        if (isset($options['cache'])) {
            $this->cache = $options['cache'];
        }

        return $this;
    }

    public function build(): HackleConfig
    {
        return new HackleConfig($this);
    }

    /**
     * @return string
     */
    public function getSdkUri(): string
    {
        return $this->sdkUri;
    }

    /**
     * @return string
     */
    public function getEventUri(): string
    {
        return $this->eventUri;
    }

    /**
     * @return string
     */
    public function getMonitoringUri(): string
    {
        return $this->monitoringUri;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return CacheMiddleware
     */
    public function getCache(): CacheMiddleware
    {
        return $this->cache;
    }
}
