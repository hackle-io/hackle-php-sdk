<?php

namespace Hackle\Tests\Internal\Model;

use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Internal\Model\TargetActionBucket;
use Hackle\Internal\Model\Variation;
use Hackle\Internal\User\HackleUser;
use Hackle\Internal\User\IdentifierType;
use Hackle\Internal\Workspace\DefaultWorkspace;
use Hackle\Internal\Workspace\Workspace;

class Models
{
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