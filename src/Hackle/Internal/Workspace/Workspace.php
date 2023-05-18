<?php

namespace Hackle\Internal\Workspace;

use Closure;
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
use Hackle\Internal\Model\Enums\SegmentType;
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
use Hackle\Internal\Utils\Arrays;
use Hackle\Internal\Utils\Enums;
use Hackle\Internal\Workspace\Dto\BucketDto;
use Hackle\Internal\Workspace\Dto\ConditionDto;
use Hackle\Internal\Workspace\Dto\ContainerDto;
use Hackle\Internal\Workspace\Dto\ContainerGroupDto;
use Hackle\Internal\Workspace\Dto\EventTypeDto;
use Hackle\Internal\Workspace\Dto\ExperimentDto;
use Hackle\Internal\Workspace\Dto\KeyDto;
use Hackle\Internal\Workspace\Dto\MatchDto;
use Hackle\Internal\Workspace\Dto\SegmentDto;
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
        $experiments = Arrays::mapNotNull($dto->getExperiments(), self::toExperimentOrNull(ExperimentType::AB_TEST));
        $featureFlags = Arrays::mapNotNull($dto->getExperiments(), self::toExperimentOrNull(ExperimentType::FEATURE_FLAG));
        $eventTypes = self::toEventTypes($dto->getEvents());
        $buckets = self::toBuckets($dto->getBuckets());
        $segments = self::toSegments($dto->getSegments());
        $containers = self::toContainers($dto->getContainers());
        $parameterConfigurations = self::toParameterConfigurations($dto->getParameterConfigurations());
        $remoteConfigurations = self::toRemoteConfigParameters($dto->getRemoteConfigParameters());
        return new Workspace($experiments, $featureFlags, $eventTypes, $buckets, $segments, $containers, $parameterConfigurations, $remoteConfigurations);
    }

    public static function toExperimentOrNull(string $type): Closure
    {
        return function (ExperimentDto $dto) use ($type): ?Experiment {
            $experimentStatus = ExperimentStatus::fromExecutionStatusOrNull($dto->getExecution()->getStatus());
            if ($experimentStatus == null) {
                return null;
            }
            $defaultRule = self::toActionOrNull($dto->getExecution()->getDefaultRule());
            if ($defaultRule == null) {
                return null;
            }
            return new Experiment($dto->getId(), $dto->getKey(), $type, $dto->getIdentifierType(), $experimentStatus, $dto->getVersion(), array_map(self::toVariation(), $dto->getVariations()), self::toUserOverrideArray($dto->getExecution()->getUserOverrides()), array_map(self::toTargetRuleOrNull(TargetingType::IDENTIFIER), $dto->getExecution()->getSegmentOverrides()), array_map(self::toTargetOrNull(TargetingType::PROPERTY), $dto->getExecution()->getTargetAudiences()), array_map(self::toTargetRuleOrNull(TargetingType::PROPERTY), $dto->getExecution()->getTargetRules()), $defaultRule, $dto->getContainerId(), $dto->getWinnerVariationId());
        };
    }

    private static function toActionOrNull(TargetActionDto $dto): ?Action
    {
        switch ($dto->getType()) {
            case "VARIATION":
                return new VariationAction($dto->getVariationId());
            case "BUCKET":
                return new BucketAction($dto->getBucketId());
        }
        return null;
    }

    private static function toVariation(): Closure
    {
        return function (VariationDto $dto) {
            return new Variation($dto->getId(), $dto->getKey(), $dto->getStatus() == "DROPPED", $dto->getParameterConfigurationId());
        };
    }

    private static function toUserOverrideArray(array $userOverrides): array
    {
        $keyMapper = function (UserOverrideDto $dto): string {
            return $dto->getUserId();
        };
        $valueMapper = function (UserOverrideDto $dto): int {
            return $dto->getVariationId();
        };
        return Arrays::associate($userOverrides, $keyMapper, $valueMapper);
    }

    private static function toTargetRuleOrNull(string $targetingType): Closure
    {
        return function (TargetRuleDto $dto) use ($targetingType): ?TargetRule {
            return new TargetRule(call_user_func(self::toTargetOrNull($targetingType), $dto->getTarget()), self::toActionOrNull($dto->getAction()));
        };
    }

    private static function toTargetOrNull(string $targetingType): Closure
    {
        return function (TargetDto $dto) use ($targetingType) {
            $conditions = array_map(self::toConditionOrNull($targetingType), $dto->getConditions());
            if (empty($conditions)) {
                return null;
            }
            return new Target($conditions);
        };
    }

    private static function toConditionOrNull(string $targetingType): Closure
    {
        return function (ConditionDto $dto) use ($targetingType): ?Condition {
            $key = self::toTargetKeyOrNull($dto->getKey());
            if ($key == null) {
                return null;
            }
            if (!TargetingType::supports($targetingType, $key->getType())) {
                return null;
            }
            $match = self::toMatchOrNull($dto->getMatch());
            if ($match == null) {
                return null;
            }
            return new Condition($key, $match);
        };
    }

    private static function toTargetKeyOrNull(KeyDto $dto): ?Key
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

    private static function toMatchOrNull(MatchDto $dto): ?Match
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

    private static function toEventTypes(array $eventTypes): array
    {
        $keyMapper = function (EventTypeDto $dto): string {
            return $dto->getKey();
        };
        return Arrays::associate($eventTypes, $keyMapper, self::toEventType());
    }

    private static function toEventType(): Closure
    {
        return function (EventTypeDto $dto): EventType {
            return new EventType($dto->getId(), $dto->getKey());
        };
    }

    private static function toBuckets(array $buckets): array
    {
        $keyMapper = function (BucketDto $dto): int {
            return $dto->getId();
        };
        return Arrays::associate($buckets, $keyMapper, self::toBucket());
    }

    private static function toBucket(): Closure
    {
        return function (BucketDto $dto): Bucket {
            return new Bucket($dto->getId(), $dto->getSeed(), $dto->getSlotSize(), $dto->getSlots());
        };
    }

    private static function toSegments(array $segments): array
    {
        $keyMapper = function (SegmentDto $dto) {
            return $dto->getKey();
        };
        return Arrays::associateBy(Arrays::mapNotNull($segments, self::toSegmentOrNull()), $keyMapper);
    }

    private static function toSegmentOrNull(): Closure
    {

        return function (SegmentDto $dto): ?Segment {
            $segmentType = Enums::parseEnumOrNull(SegmentType::class, $dto->getType());
            if ($segmentType == null) {
                return null;
            }
            return new Segment($dto->getId(), $dto->getKey(), $segmentType, Arrays::mapNotNull($dto->getTargets(), self::toTargetOrNull(TargetingType::SEGMENT)));
        };
    }

    private static function toContainers(array $containers): array
    {
        $keyMapper = function (ContainerDto $dto) {
            return $dto->getId();
        };
        return Arrays::associateBy(array_map(self::toContainer(), $containers), $keyMapper);
    }

    private static function toContainer(): Closure
    {
        return function (ContainerDto $dto): Container {
            return new Container($dto->getId(), $dto->getBucketId(), array_map(self::toContainerGroup(), $dto->getGroups()));
        };
    }

    private static function toContainerGroup(): Closure
    {
        return function (ContainerGroupDto $dto): ContainerGroupDto {
            return new ContainerGroupDto($dto->getId(), $dto->getExperiments());
        };
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
