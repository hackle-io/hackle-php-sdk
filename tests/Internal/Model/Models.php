<?php

namespace Hackle\Tests\Internal\Model;

use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Internal\Model\TargetAction;
use Hackle\Internal\Model\TargetActionBucket;
use Hackle\Internal\Model\Variation;
use Hackle\Internal\User\HackleUser;
use Hackle\Internal\User\IdentifierType;
use Hackle\Internal\Workspace\DefaultWorkspace;
use Hackle\Internal\Workspace\Workspace;

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

    public static function experiment(array $params = []): Experiment
    {
        return new Experiment(
            $params["id"] ?? 1,
            $params["key"] ?? 1,
            $params["type"] ?? ExperimentType::AB_TEST(),
            $params["identifierType"] ?? IdentifierType::ID(),
            $params["status"] ?? ExperimentStatus::RUNNING(),
            $params["version"] ?? 1,
            $params["variations"] ?? [Models::variation(1, "A"), Models::variation(2, "B")],
            $params["userOverrides"] ?? [],
            $params["segmentOverrides"] ?? [],
            $params["targetAudiences"] ?? [],
            $params["targetRules"] ?? [],
            $params["defaultRule"] ?? new TargetActionBucket(1),
            $params["containerId"] ?? null,
            $params["winnerVariationId"] ?? null
        );
    }

    public static function variation(int $id, string $key, bool $isDropped = false, ?int $configId = null): Variation
    {
        return new Variation($id, $key, $isDropped, $configId);
    }

    public static function experimentRequest(
        ?Experiment $experiment = null,
        ?Workspace $workspace = null,
        ?HackleUser $user = null
    ): ExperimentRequest {
        return ExperimentRequest::of(
            $workspace ?? DefaultWorkspace::from(array()),
            $user ?? HackleUser::builder()->identifier(IdentifierType::ID, "user")->build(),
            $experiment ?? Models::experiment(),
            "A"
        );
    }
}