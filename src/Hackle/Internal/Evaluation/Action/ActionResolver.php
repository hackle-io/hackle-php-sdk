<?php

namespace Hackle\Internal\Evaluation\Action;

use Hackle\Internal\Evaluation\Bucket\Bucketer;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\TargetAction;
use Hackle\Internal\Model\TargetActionBucket;
use Hackle\Internal\Model\TargetActionVariation;
use Hackle\Internal\Model\Variation;

final class ActionResolver
{

    private $bucketer;

    public function __construct(Bucketer $bucketer)
    {
        $this->bucketer = $bucketer;
    }

    public function resolveOrNull(ExperimentRequest $request, TargetAction $action): ?Variation
    {
        if ($action instanceof TargetActionVariation) {
            return $this->resolveVariation($request, $action);
        }

        if ($action instanceof TargetActionBucket) {
            return $this->resolveBucket($request, $action);
        }

        return null;
    }

    private function resolveVariation(ExperimentRequest $request, TargetActionVariation $action): Variation
    {
        return Objects::requireNotNull(
            $request->getExperiment()->getVariationOrNullById($action->getVariationId()),
            "Variation[{$action->getVariationId()}]"
        );
    }

    private function resolveBucket(ExperimentRequest $request, TargetActionBucket $action): ?Variation
    {
        $bucket = Objects::requireNotNull(
            $request->getWorkspace()->getBucketOrNull($action->getBucketId()),
            "Bucket[{$action->getBucketId()}]"
        );

        $identifier = $request->getUser()->getIdentifiers()[$request->getExperiment()->getIdentifierType()] ?? null;
        if ($identifier === null) {
            return null;
        }

        $slot = $this->bucketer->bucketing($bucket, $identifier);
        if ($slot === null) {
            return null;
        }

        return $request->getExperiment()->getVariationOrNullById($slot->getVariationId());
    }
}