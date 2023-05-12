<?php

namespace Hackle\Internal\Workspace\Dto;

class WorkspaceDto
{
    /**@var array */
    private $_experiments;

    /**@var array */
    private $_featureFlags;

    /**@var array */
    private $_buckets;

    /**@var array */
    private $_events;

    /**@var array */
    private $_segments;

    /**@var array */
    private $_containers;

    /**@var array */
    private $_parameterConfigurations;

    /**@var array */
    private $_remoteConfigParameters;

    /**
     * @return array
     */
    public function getExperiments(): array
    {
        return $this->_experiments;
    }

    /**
     * @return array
     */
    public function getFeatureFlags(): array
    {
        return $this->_featureFlags;
    }

    /**
     * @return array
     */
    public function getBuckets(): array
    {
        return $this->_buckets;
    }

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->_events;
    }

    /**
     * @return array
     */
    public function getSegments(): array
    {
        return $this->_segments;
    }

    /**
     * @return array
     */
    public function getContainers(): array
    {
        return $this->_containers;
    }

    /**
     * @return array
     */
    public function getParameterConfigurations(): array
    {
        return $this->_parameterConfigurations;
    }

    /**
     * @return array
     */
    public function getRemoteConfigParameters(): array
    {
        return $this->_remoteConfigParameters;
    }
}
