<?php

namespace Hackle\Internal\Workspace\Dto;

class WorkspaceDto
{
    /**@var ExperimentDto[] */
    private $_experiments;

    /**@var ExperimentDto[] */
    private $_featureFlags;

    /**@var BucketDto[] */
    private $_buckets;

    /**@var EventTypeDto[] */
    private $_events;

    /**@var SegmentDto[] */
    private $_segments;

    /**@var ContainerDto[] */
    private $_containers;

    /**@var ParameterConfigurationDto[] */
    private $_parameterConfigurations;

    /**@var RemoteConfigParameterDto[] */
    private $_remoteConfigParameters;

    /**
     * @return ExperimentDto[]
     */
    public function getExperiments(): array
    {
        return $this->_experiments;
    }

    /**
     * @return ExperimentDto[]
     */
    public function getFeatureFlags(): array
    {
        return $this->_featureFlags;
    }

    /**
     * @return BucketDto[]
     */
    public function getBuckets(): array
    {
        return $this->_buckets;
    }

    /**
     * @return EventTypeDto[]
     */
    public function getEvents(): array
    {
        return $this->_events;
    }

    /**
     * @return SegmentDto[]
     */
    public function getSegments(): array
    {
        return $this->_segments;
    }

    /**
     * @return ContainerDto[]
     */
    public function getContainers(): array
    {
        return $this->_containers;
    }

    /**
     * @return ParameterConfigurationDto[]
     */
    public function getParameterConfigurations(): array
    {
        return $this->_parameterConfigurations;
    }

    /**
     * @return RemoteConfigParameterDto[]
     */
    public function getRemoteConfigParameters(): array
    {
        return $this->_remoteConfigParameters;
    }
}
