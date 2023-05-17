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
     * @param ExperimentDto[] $_experiments
     * @param ExperimentDto[] $_featureFlags
     * @param BucketDto[] $_buckets
     * @param EventTypeDto[] $_events
     * @param SegmentDto[] $_segments
     * @param ContainerDto[] $_containers
     * @param ParameterConfigurationDto[] $_parameterConfigurations
     * @param RemoteConfigParameterDto[] $_remoteConfigParameters
     */
    public function __construct(array $_experiments, array $_featureFlags, array $_buckets, array $_events, array $_segments, array $_containers, array $_parameterConfigurations, array $_remoteConfigParameters)
    {
        $this->_experiments = $_experiments;
        $this->_featureFlags = $_featureFlags;
        $this->_buckets = $_buckets;
        $this->_events = $_events;
        $this->_segments = $_segments;
        $this->_containers = $_containers;
        $this->_parameterConfigurations = $_parameterConfigurations;
        $this->_remoteConfigParameters = $_remoteConfigParameters;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self(array_map(ExperimentDto::getDecoder(), $v["experiments"]), array_map(ExperimentDto::getDecoder(), $v["featureFlags"]), array_map(BucketDto::getDecoder(), $v["buckets"]), array_map(EventTypeDto::getDecoder(), $v["events"]), array_map(SegmentDto::getDecoder(), $v["segments"]), array_map(ContainerDto::getDecoder(), $v["containers"]), array_map(ParameterConfigurationDto::getDecoder(), $v["parameterConfigurations"]), array_map(RemoteConfigParameterDto::getDecoder(), $v["remoteConfigParameters"]));
        };
    }

    public static function decode(array $v): self
    {
        $decoder = self::getDecoder();
        return $decoder($v);
    }

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
