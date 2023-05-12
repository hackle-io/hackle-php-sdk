<?php

namespace Hackle\Internal\User\Workspace;

use Hackle\Internal\Model\Bucket;
use Hackle\Internal\Model\Container;
use Hackle\Internal\Model\EventType;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\Model\ParameterConfiguration;
use Hackle\Internal\Model\RemoteConfigParameter;
use Hackle\Internal\Model\Segment;
use Hackle\Internal\Workspace\Dto\WorkspaceDto;

class Workspace
{
    /**@var array */
    private $_experiments;

    /**@var array */
    private $_featureFlags;

    /**@var array */
    private $_eventTypes;

    /**@var array */
    private $_buckets;

    /**@var array */
    private $_segments;

    /**@var array */
    private $_containers;

    /**@var array */
    private $_parameterConfigurations;

    /**@var array */
    private $_remoteConfigParameters;

    private function __construct(array $_experiments, array $_featureFlags, array $_eventTypes, array $_buckets, array $_segments, array $_containers, array $_parameterConfigurations, array $_remoteConfigParameters)
    {
        $this->_experiments = $_experiments;
        $this->_featureFlags = $_featureFlags;
        $this->_eventTypes = $_eventTypes;
        $this->_buckets = $_buckets;
        $this->_segments = $_segments;
        $this->_containers = $_containers;
        $this->_parameterConfigurations = $_parameterConfigurations;
        $this->_remoteConfigParameters = $_remoteConfigParameters;
    }

    public function getExperimentOrNull(int $experimentKey): ?Experiment
    {
        return $this->_experiments[$experimentKey];
    }

    public function getFeatureFlagOrNull(int $featureKey): ?Experiment
    {
        return $this->_featureFlags[$featureKey];
    }

    public function getEventTypeOrNull(string $eventTypeKey): ?EventType
    {
        return $this->_eventTypes[$eventTypeKey];
    }

    public function getBucketOrNull(int $bucketId): ?Bucket
    {
        return$this->_buckets[$bucketId];
    }

    public function getSegmentOrNull(string $segmentKey): ?Segment
    {
        return $this->_segments[$segmentKey];
    }

    public function getContainerOrNull(int $containerId): ?Container
    {
        return $this->_containers[$containerId];
    }

    public function getParameterConfigurationOrNull(int $parameterConfigurationId): ?ParameterConfiguration
    {
        return $this->_parameterConfigurations[$parameterConfigurationId];
    }

    public function getRemoteConfigParameterOrNull(string $parameterKey): ?RemoteConfigParameter
    {
        return $this->_remoteConfigParameters[$parameterKey];
    }

    public static function from(WorkspaceDto $dto): self
    {
        $experiments = self::toExperiments($dto->getExperiments());
        $featureFlags = self::toFeatureFlags($dto->getFeatureFlags());
        $eventTypes = self::toEventTypes($dto->getEvents());
        $buckets = self::toBuckets($dto->getBuckets());
        $segments = self::toSegments($dto->getSegments());
        $containers = self::toContainers($dto->getContainers());
        $parameterConfigurations = self::toParameterConfigurations($dto->getParameterConfigurations());
        $remoteConfigurations = self::toRemoteConfigParameters($dto->getRemoteConfigParameters());
        return new Workspace($experiments, $featureFlags, $eventTypes, $buckets, $segments, $containers, $parameterConfigurations, $remoteConfigurations);
    }

    private static function toExperiments(array $experiments): array
    {
        return array();
    }

    private static function toFeatureFlags(array $featureFlags): array
    {
        return array();
    }

    private static function toEventTypes(array $eventTypes): array
    {
        return array();
    }

    private static function toBuckets(array $buckets): array
    {
        return array();
    }

    private static function toSegments(array $segments): array
    {
        return array();
    }

    private static function toContainers(array $containers): array
    {
        return array();
    }

    private static function toParameterConfigurations(array $parameterConfigurations): array
    {
        return array();
    }

    private static function toRemoteConfigParameters(array $remoteConfigParameters): array
    {
        return array();
    }
}
