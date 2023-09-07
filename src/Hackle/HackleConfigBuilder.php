<?php

namespace Hackle;

use Hackle\Common\HackleRegion;

final class HackleConfigBuilder
{
    /**@var string */
    private $sdkUri;

    /**@var string */
    private $eventUri;

    /**@var string */
    private $monitoringUri;

    public function __construct()
    {
        $defaultRegion = HackleRegion::defaultRegion();
        $this->sdkUri = $defaultRegion->getSdkUri();
        $this->eventUri = $defaultRegion->getEventUri();
        $this->monitoringUri = $defaultRegion->getMonitoringUri();
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
