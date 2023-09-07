<?php

namespace Hackle\Common;

final class HackleRegion
{
    /**
     * @var string
     */
    private $sdkUri;

    /**
     * @var string
     */
    private $eventUri;

    /**
     * @var string
     */
    private $monitoringUri;

    /**
     * @param string $sdkUrl
     * @param string $eventUrl
     * @param string $monitoringUrl
     */
    public function __construct(string $sdkUrl, string $eventUrl, string $monitoringUrl)
    {
        $this->sdkUri = $sdkUrl;
        $this->eventUri = $eventUrl;
        $this->monitoringUri = $monitoringUrl;
    }

    public static function defaultRegion(): HackleRegion
    {
        return new HackleRegion(
            "https://sdk.hackle.io",
            "https://event.hackle.io",
            "https://monitoring.hackle.io"
        );
    }

    public static function staticRegion(): HackleRegion
    {
        return new HackleRegion(
            "https://static-sdk.hackle.io",
            "https://static-event.hackle.io",
            "https://static-monitoring.hackle.io"
        );
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
