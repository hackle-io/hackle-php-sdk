<?php

namespace Hackle\Internal\Workspace;

use Hackle\Internal\Lang\Pair;
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
    private $experiments;
    private $featureFlags;
    private $eventTypes;
    private $buckets;
    private $segments;
    private $containers;
    private $parameterConfigurations;
    private $remoteConfigParameters;

    /**
     * @param $experiments
     * @param $featureFlags
     * @param $eventTypes
     * @param $buckets
     * @param $segments
     * @param $containers
     * @param $parameterConfigurations
     * @param $remoteConfigParameters
     */
    public function __construct(
        $experiments,
        $featureFlags,
        $eventTypes,
        $buckets,
        $segments,
        $containers,
        $parameterConfigurations,
        $remoteConfigParameters
    ) {
        $this->experiments = $experiments;
        $this->featureFlags = $featureFlags;
        $this->eventTypes = $eventTypes;
        $this->buckets = $buckets;
        $this->segments = $segments;
        $this->containers = $containers;
        $this->parameterConfigurations = $parameterConfigurations;
        $this->remoteConfigParameters = $remoteConfigParameters;
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
        $experiments = Arrays::associateBy(
            Arrays::mapNotNull($data["experiments"] ?? [], function ($data) {
                return Experiment::fromOrNull($data, ExperimentType::AB_TEST());
            }),
            function (Experiment $experiment) {
                return $experiment->getKey();
            }
        );

        $featureFlags = Arrays::associateBy(
            Arrays::mapNotNull($data["featureFlags"] ?? [], function ($data) {
                return Experiment::fromOrNull($data, ExperimentType::FEATURE_FLAG());
            }),
            function (Experiment $featureFlag) {
                return $featureFlag->getKey();
            }
        );

        $eventTypes = Arrays::associate($data["events"] ?? [], function ($data) {
            return new Pair($data["key"], EventType::from($data));
        });

        $buckets = Arrays::associate($data["buckets"] ?? [], function ($data) {
            return new Pair($data["id"], Bucket::from($data));
        });

        $segments = Arrays::associateBy(
            Arrays::mapNotNull($data["segments"] ?? [], function ($data) {
                return Segment::fromOrNull($data);
            }),
            function (Segment $segment) {
                return $segment->getId();
            }
        );

        $containers = Arrays::associateBy(
            array_map(function ($data) {
                return Container::from($data);
            }, $data["containers"] ?? []),
            function (Container $container) {
                return $container->getId();
            }
        );

        $parameterConfigurations = Arrays::associateBy(
            array_map(function ($data) {
                return ParameterConfiguration::from($data);
            }, $data["parameterConfigurations"] ?? []),
            function (ParameterConfiguration $parameterConfiguration) {
                return $parameterConfiguration->getId();
            }
        );

        $remoteConfigParameters = Arrays::associateBy(
            Arrays::mapNotNull($data["remoteConfigParameters"] ?? [], function ($data) {
                return RemoteConfigParameter::fromOrNull($data);
            }),
            function (RemoteConfigParameter $parameter) {
                return $parameter->getKey();
            }
        );

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
