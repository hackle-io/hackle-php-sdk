<?php

namespace Hackle;

use Psr\Log\LoggerInterface;

final class HackleConfigBuilder
{
    const DEFAULT_SDK_URI = "https://sdk.hackle.io";
    const DEFAULT_EVENT_URI = "https//event.hackle.io";
    const DEFAULT_MONITORING_URI = "https//monitoring.hackle.io";
    private $_sdkUri;
    private $_eventUri;
    private $_monitoringUri;

    /** @var LoggerInterface */
    private $_logger;

    public function __construct()
    {
    }

    public function sdkUri(string $sdkUri): HackleConfigBuilder
    {
        $this->_sdkUri = rtrim($sdkUri, '/');
        return $this;
    }

    public function eventUri(string $eventUri): HackleConfigBuilder
    {
        $this->_eventUri = rtrim($eventUri, '/');
        return $this;
    }

    public function monitoringUri(string $monitoringUri): HackleConfigBuilder
    {
        $this->_monitoringUri = rtrim($monitoringUri, '/');
        return $this;
    }

    public function logger(LoggerInterface $logger): HackleConfigBuilder
    {
        $this->_logger = $logger;
        return $this;
    }

    public function build(): HackleConfig
    {
        return new HackleConfig($this);
    }

    public function getSdkUri()
    {
        return $this->_sdkUri;
    }

    public function getEventUri()
    {
        return $this->_eventUri;
    }

    public function getMonitoringUri()
    {
        return $this->_monitoringUri;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->_logger;
    }
}
