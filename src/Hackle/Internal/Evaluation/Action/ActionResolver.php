<?php

namespace Hackle\Internal\Evaluation\Action;

use Hackle\Internal\Evaluation\Bucket\Bucketer;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\Action;
use Hackle\Internal\Model\BucketAction;
use Hackle\Internal\Model\Variation;
use Hackle\Internal\Model\VariationAction;

final class ActionResolver
{

    private $bucketer;

    public function __construct(Bucketer $bucketer)
    {
        $this->bucketer = $bucketer;
    }

    public function resolveOrNull(ExperimentRequest $request, Action $action): ?Variation
    {
        if ($action instanceof VariationAction) {
            return $this->resolveVariation($request, $action);
        }

        if ($action instanceof BucketAction) {
            return $this->resolveBucket($request, $action);
        }

        return null;
    }

    private function resolveVariation(ExperimentRequest $request, VariationAction $action): Variation
    {
        return Objects::requireNotNull(
            $request->getExperiment()->getVariationOrNullById($action->getVariationId()),
            "Variation[{$action->getVariationId()}]"
        );
    }

    private function resolveBucket(ExperimentRequest $request, BucketAction $action): ?Variation
    {
        $bucket = Objects::requireNotNull(
            $request->getWorkspace()->getBucketOrNull($action->getBucketId()),
            "Bucket[{$action->getBucketId()}]"
        );

        $identifier = $request->getUser()->getIdentifiers()[$request->getExperiment()->getIdentifierType()];
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