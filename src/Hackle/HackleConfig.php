<?php

namespace Hackle;

final class HackleConfig
{
    /**@var string */
    private $sdkUri;

    /**@var string */
    private $eventUri;

    /**@var string */
    private $monitoringUri;

    public function __construct(HackleConfigBuilder $builder)
    {
        $this->sdkUri = $builder->getSdkUri();
        $this->eventUri = $builder->getEventUri();
        $this->monitoringUri = $builder->getMonitoringUri();
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
}
