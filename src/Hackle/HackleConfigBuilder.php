<?php

namespace Hackle;

use Hackle\Common\HackleRegion;

final class HackleConfigBuilder
{
    public const DEFAULT_SDK_URI = "https://sdk.hackle.io";
    public const DEFAULT_EVENT_URI = "https://event.hackle.io";
    public const DEFAULT_MONITORING_URI = "https://monitoring.hackle.io";

    /**@var string */
    private $sdkUri;

    /**@var string */
    private $eventUri;

    /**@var string */
    private $monitoringUri;

    public function __construct()
    {
        $this->sdkUri = self::DEFAULT_SDK_URI;
        $this->eventUri = self::DEFAULT_EVENT_URI;
        $this->monitoringUri = self::DEFAULT_MONITORING_URI;
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

    public function region(HackleRegion $region): self
    {
        $this->sdkUri($region->getSdkUri());
        $this->eventUri($region->getEventUri());
        $this->monitoringUri($region->getMonitoringUri());
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
}
