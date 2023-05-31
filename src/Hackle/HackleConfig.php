<?php

namespace Hackle;

use Kevinrob\GuzzleCache\CacheMiddleware;
use Psr\Log\LoggerInterface;

final class HackleConfig
{
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

    public function __construct(HackleConfigBuilder $builder)
    {
        $this->sdkUri = $builder->getSdkUri();
        $this->eventUri = $builder->getEventUri();
        $this->monitoringUri = $builder->getMonitoringUri();
        $this->logger = $builder->getLogger();
        $this->cache = $builder->getCache();
    }

    public static function getDefault(): HackleConfig
    {
        return self::builder()->build();
    }

    public static function builder(): HackleConfigBuilder
    {
        return new HackleConfigBuilder();
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
