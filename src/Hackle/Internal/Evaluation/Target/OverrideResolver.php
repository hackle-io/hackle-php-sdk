<?php

namespace Hackle\Internal\Evaluation\Target;

use Hackle\Internal\Evaluation\Action\ActionResolver;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Match\TargetMatcher;
use Hackle\Internal\Model\Variation;

class OverrideResolver
{
    private $targetMatcher;
    private $actionResolver;

    public function __construct(TargetMatcher $targetMatcher, ActionResolver $actionResolver)
    {
        $this->targetMatcher = $targetMatcher;
        $this->actionResolver = $actionResolver;
    }

    public function resolveOrNull(ExperimentRequest $request, EvaluatorContext $context): ?Variation
    {
        return $this->resolveUserOverride($request, $context)
            ?? $this->resolveSegmentOverride($request, $context);
    }

    private function resolveUserOverride(ExperimentRequest $request, EvaluatorContext $context): ?Variation
    {
        $experiment = $request->getExperiment();

        $identifier = $request->getUser()->getIdentifiers()[$experiment->getIdentifierType()] ?? null;
        if ($identifier === null) {
            return null;
        }

        $overriddenVariationId = $experiment->getUserOverrides()[$identifier] ?? null;
        if ($overriddenVariationId === null) {
            return null;
        }

        return $experiment->getVariationOrNullById($overriddenVariationId);
    }

    private function resolveSegmentOverride(ExperimentRequest $request, EvaluatorContext $context): ?Variation
    {
        foreach ($request->getExperiment()->getSegmentOverrides() as $segmentOverride) {
            if ($this->targetMatcher->matches($request, $context, $segmentOverride->getTarget())) {
                return $this->actionResolver->resolveOrNull($request, $segmentOverride->getAction());
            }
        }
        return null;
    }
}
