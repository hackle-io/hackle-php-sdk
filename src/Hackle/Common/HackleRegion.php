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
     * @param string $sdkUri
     * @param string $eventUri
     * @param string $monitoringUri
     */
    public function __construct(string $sdkUri, string $eventUri, string $monitoringUri)
    {
        $this->sdkUri = $sdkUri;
        $this->eventUri = $eventUri;
        $this->monitoringUri = $monitoringUri;
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
