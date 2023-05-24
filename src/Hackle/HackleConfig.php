<?php

namespace Hackle;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class HackleConfig
{
    const DEFAULT_SDK_URI = "https://sdk.hackle.io";
    const DEFAULT_EVENT_URI = "https://event.hackle.io";
    const DEFAULT_MONITORING_URI = "https//monitoring.hackle.io";
    private $_sdkUri;
    private $_eventUri;
    private $_monitoringUri;

    /** @var LoggerInterface */
    private $_logger;

    public function __construct(HackleConfigBuilder $builder)
    {
        $this->_sdkUri = $builder->getSdkUri();
        $this->_eventUri = $builder->getEventUri();
        $this->_monitoringUri = $builder->getMonitoringUri();
        $this->_logger = $builder->getLogger();
    }

    public static function getDefault(): HackleConfig
    {
        return self::builder()
            ->sdkUri(self::DEFAULT_SDK_URI)
            ->eventUri(self::DEFAULT_EVENT_URI)
            ->monitoringUri(self::DEFAULT_MONITORING_URI)
            ->logger(new Logger("Hackle", [new ErrorLogHandler()]))->build();
    }

    public static function builder(): HackleConfigBuilder
    {
        return new HackleConfigBuilder();
    }

    public function getSdkUri(): string
    {
        return $this->_sdkUri;
    }

    public function getEventUri(): string
    {
        return $this->_eventUri;
    }

    public function getMonitoringUri(): string
    {
        return $this->_monitoringUri;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->_logger;
    }
}
