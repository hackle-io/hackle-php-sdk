<?php

namespace Hackle\Internal\Workspace;

use Hackle\Internal\Model\Bucket;
use Hackle\Internal\Model\Container;
use Hackle\Internal\Model\EventType;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Internal\Model\ParameterConfiguration;
use Hackle\Internal\Model\RemoteConfigParameter;
use Hackle\Internal\Model\Segment;
use Hackle\Internal\Utils\Arrays;

class DefaultWorkspace implements Workspace
{
    /** @var array<int, Experiment> */
    private $experiments;

    /** @var array<int, Experiment> */
    private $featureFlags;

    /** @var array<string, EventType> */
    private $eventTypes;

    /** @var array<int, Bucket> */
    private $buckets;

    /** @var array<string, Segment> */
    private $segments;

    /** @var array<int, Container> */
    private $containers;

    /** @var array<int, ParameterConfiguration> */
    private $parameterConfigurations;

    /** @var array<string, RemoteConfigParameter> */
    private $remoteConfigParameters;

    /**
     * @param Experiment[] $experiments
     * @param Experiment[] $featureFlags
     * @param EventType[] $eventTypes
     * @param Bucket[] $buckets
     * @param Segment[] $segments
     * @param Container[] $containers
     * @param ParameterConfiguration[] $parameterConfigurations
     * @param RemoteConfigParameter[] $remoteConfigParameters
     */
    public function __construct(
        array $experiments,
        array $featureFlags,
        array $eventTypes,
        array $buckets,
        array $segments,
        array $containers,
        array $parameterConfigurations,
        array $remoteConfigParameters
    ) {
        $this->experiments = Arrays::associateBy($experiments, function (Experiment $experiment) {
            return $experiment->getKey();
        });
        $this->featureFlags = Arrays::associateBy($featureFlags, function (Experiment $featureFlag) {
            return $featureFlag->getKey();
        });
        $this->eventTypes = Arrays::associateBy($eventTypes, function (EventType $eventType) {
            return $eventType->getKey();
        });
        $this->buckets = Arrays::associateBy($buckets, function (Bucket $bucket) {
            return $bucket->getId();
        });
        $this->segments = Arrays::associateBy($segments, function (Segment $segment) {
            return $segment->getKey();
        });
        $this->containers = Arrays::associateBy($containers, function (Container $container) {
            return $container->getId();
        });
        $this->parameterConfigurations = Arrays::associateBy(
            $parameterConfigurations,
            function (ParameterConfiguration $parameterConfiguration) {
                return $parameterConfiguration->getId();
            }
        );
        $this->remoteConfigParameters = Arrays::associateBy(
            $remoteConfigParameters,
            function (RemoteConfigParameter $parameter) {
                return $parameter->getKey();
            }
        );
    }


    public function getExperimentOrNull(int $experimentKey): ?Experiment
    {
        return $this->experiments[$experimentKey] ?? null;
    }

    public function getFeatureFlagOrNull(int $featureKey): ?Experiment
    {
        return $this->featureFlags[$featureKey] ?? null;
    }

    public function getEventTypeOrNull(string $eventTypeKey): ?EventType
    {
        return $this->eventTypes[$eventTypeKey] ?? null;
    }

    public function getBucketOrNull(int $bucketId): ?Bucket
    {
        return $this->buckets[$bucketId] ?? null;
    }

    public function getSegmentOrNull(string $segmentKey): ?Segment
    {
        return $this->segments[$segmentKey] ?? null;
    }

    public function getContainerOrNull(int $containerId): ?Container
    {
        return $this->containers[$containerId] ?? null;
    }

    public function getParameterConfigurationOrNull(int $parameterConfigurationId): ?ParameterConfiguration
    {
        return $this->parameterConfigurations[$parameterConfigurationId] ?? null;
    }

    public function getRemoteConfigParameterOrNull(string $parameterKey): ?RemoteConfigParameter
    {
        return $this->remoteConfigParameters[$parameterKey] ?? null;
    }

    public static function from(array $data): ?self
    {
        $experiments = Arrays::mapNotNull($data["experiments"] ?? [], function ($data) {
            return Experiment::fromOrNull($data, ExperimentType::AB_TEST());
        });

        $featureFlags = Arrays::mapNotNull($data["featureFlags"] ?? [], function ($data) {
            return Experiment::fromOrNull($data, ExperimentType::FEATURE_FLAG());
        });

        $eventTypes = Arrays::mapNotNull($data["events"] ?? [], function ($data) {
            return EventType::from($data);
        });

        $buckets = Arrays::mapNotNull($data["buckets"] ?? [], function ($data) {
            return Bucket::from($data);
        });

        $segments = Arrays::mapNotNull($data["segments"] ?? [], function ($data) {
            return Segment::fromOrNull($data);
        });

        $containers = Arrays::mapNotNull($data["containers"] ?? [], function ($data) {
            return Container::from($data);
        });

        $parameterConfigurations = Arrays::mapNotNull($data["parameterConfigurations"] ?? [], function ($data) {
            return ParameterConfiguration::from($data);
        });

        $remoteConfigParameters = Arrays::mapNotNull($data["remoteConfigParameters"] ?? [], function ($data) {
            return RemoteConfigParameter::fromOrNull($data);
        });

        return new DefaultWorkspace(
            $experiments,
            $featureFlags,
            $eventTypes,
            $buckets,
            $segments,
            $containers,
            $parameterConfigurations,
            $remoteConfigParameters
        );
    }
}
