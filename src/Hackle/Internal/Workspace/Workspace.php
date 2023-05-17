<?php

namespace Hackle\Internal\User\Workspace;

use Hackle\Internal\Model\Action;
use Hackle\Internal\Model\Bucket;
use Hackle\Internal\Model\BucketAction;
use Hackle\Internal\Model\Condition;
use Hackle\Internal\Model\Container;
use Hackle\Internal\Model\Enums\ExperimentStatus;
use Hackle\Internal\Model\Enums\ExperimentType;
use Hackle\Internal\Model\Enums\KeyType;
use Hackle\Internal\Model\Enums\MatchType;
use Hackle\Internal\Model\Enums\Operator;
use Hackle\Internal\Model\Enums\ValueType;
use Hackle\Internal\Model\EventType;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\Model\Key;
use Hackle\Internal\Model\Match;
use Hackle\Internal\Model\ParameterConfiguration;
use Hackle\Internal\Model\RemoteConfigParameter;
use Hackle\Internal\Model\Segment;
use Hackle\Internal\Model\Target;
use Hackle\Internal\Model\TargetingType;
use Hackle\Internal\Model\TargetRule;
use Hackle\Internal\Model\Variation;
use Hackle\Internal\Model\VariationAction;
use Hackle\Internal\Workspace\Dto\ConditionDto;
use Hackle\Internal\Workspace\Dto\ExperimentDto;
use Hackle\Internal\Workspace\Dto\KeyDto;
use Hackle\Internal\Workspace\Dto\MatchDto;
use Hackle\Internal\Workspace\Dto\TargetActionDto;
use Hackle\Internal\Workspace\Dto\TargetDto;
use Hackle\Internal\Workspace\Dto\TargetRuleDto;
use Hackle\Internal\Workspace\Dto\UserOverrideDto;
use Hackle\Internal\Workspace\Dto\VariationDto;
use Hackle\Internal\Workspace\Dto\WorkspaceDto;
use ReflectionException;

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
        return $this->_buckets[$bucketId];
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

    //public function toExperimentOrNull(): \Closure
    //{
    //    return function (ExperimentDto $dto, ExperimentType $type): ?Experiment {
    //        $experimentStatus = ExperimentStatus::fromExecutionStatusOrNull($dto->getExecution()->getStatus());
    //        if ($experimentStatus == null) {
    //            return null;
    //        }
    //        $defaultRule = $this->toActionOrNull($dto->getExecution()->getDefaultRule());
    //        if ($defaultRule == null) {
    //            return null;
    //        }
    //        return new Experiment($dto->getId(), $dto->getKey(), $type, $dto->getIdentifierType(), $experimentStatus, $dto->getVersion(), array_map(self::toVariation(), $dto->getVariations()), self::toUserOverrideArray($dto->getExecution()->getUserOverrides()),
    //
    //        );
    //    };
    //}

    private function toActionOrNull(TargetActionDto $dto): ?Action
    {
        switch ($dto->getType()) {
            case "VARIATION":
                return new VariationAction($dto->getVariationId());
            case "BUCKET":
                return new BucketAction($dto->getBucketId());
        }
        return null;
    }

    private function toVariation(): \Closure
    {
        return function (VariationDto $dto) {
            return new Variation($dto->getId(), $dto->getKey(), $dto->getStatus() == "DROPPED", $dto->getParameterConfigurationId());
        };
    }

    private function toUserOverrideArray(array $userOverrides): array
    {
        $groupedUserOverrides = array();
        $keys = array_map(function (UserOverrideDto $dto) {
            return $dto->getUserId();
        }, $userOverrides);
        foreach ($keys as $key) {
            $groupedUserOverrides[$key] = $userOverrides[$key];
        }
        return $groupedUserOverrides;
    }

    private function toTargetRuleOrNull(TargetingType $targetingType): \Closure
    {
        return function (TargetRuleDto $dto) use ($targetingType) {
            return new TargetRule();
        };
    }

    //private function toTargetOrNull(TargetingType $targetingType): \Closure
    //{
    //    return function (TargetDto $dto) use ($targetingType) {
    //
    //        return new Target()
    //    }
    //}
    //
    //private function toConditionOrNull(TargetingType $targetingType): \Closure
    //{
    //    return function (ConditionDto $dto) use ($targetingType): ?Condition {
    //        $key = $this->toTargetKeyOrNull($dto->getKey());
    //        if($key == null) {
    //            return null;
    //        }
    //
    //        if(TargetingType)
    //
    //    };
    //}

    private function toTargetKeyOrNull(KeyDto $dto): ?Key
    {
        try {
            if (!KeyType::isValidKey($dto->getType())) {
                return null;
            }
            return new Key(new KeyType($dto->getType()), $dto->getName());
        } catch (ReflectionException $e) {
            return null;
        }
    }

    private function toMatchOrNull(MatchDto $dto): ?Match
    {
        try {
            if (!MatchType::isValidKey($dto->getType())) {
                return null;
            }
            if (!Operator::isValidKey($dto->getOperator())) {
                return null;
            }
            if (!ValueType::isValidKey($dto->getValueType())) {
                return null;
            }
            return new Match(new MatchType($dto->getType()), new Operator($dto->getOperator()), new ValueType($dto->getValueType()), $dto->getValues());
        } catch (ReflectionException $e) {
            return null;
        }
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
