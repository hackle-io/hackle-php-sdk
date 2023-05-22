<?php

namespace Internal\Model;


use Hackle\Internal\Model\Experiment;
use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Internal\Model\TargetAction;
use Hackle\Internal\Model\TargetActionBucket;
use Hackle\Internal\Model\Variation;

class Models
{
    public static function getDefaultVariations(): array
    {
        return array(new Variation(1, "A", false, null), new Variation(2, "B", false, null),);
    }

    public static function getExperiment(
        int $id = 1,
        int $key = 1,
        string $type = ExperimentType::AB_TEST,
        string $identifierType = "\$id",
        string $status = ExperimentStatus::RUNNING,
        int $version = 1,
        array $variations = null,
        array $userOverrides = [],
        array $segmentOverrides = [],
        array $targetAudiences = [],
        array $targetRules = [],
        TargetAction $defaultRule = null,
        int $containerId = null,
        int $winnerVariationId = null
    ): Experiment {

        if ($variations == null) {
            $variations = self::getDefaultVariations();
        }
        if ($defaultRule == null) {
            $defaultRule = new TargetActionBucket(1);
        }
        return new Experiment($id, $key, new ExperimentType($type), $identifierType, ExperimentStatus::fromExecutionStatusOrNull($status), $version, $variations, $userOverrides, $segmentOverrides, $targetAudiences, $targetRules, $defaultRule, $containerId, $winnerVariationId);
    }
}
