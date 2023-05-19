<?php

namespace Hackle\Internal\Evaluation\Target;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Match\TargetMatcher;
use Hackle\Internal\Model\TargetRule;

final class ExperimentTargetRuleDeterminer
{
    private $targetMatcher;

    public function __construct(TargetMatcher $targetMatcher)
    {
        $this->targetMatcher = $targetMatcher;
    }

    public function determineTargetRuleOrNull(ExperimentRequest $request, EvaluatorContext $context): ?TargetRule
    {
        foreach ($request->getExperiment()->getTargetRules() as $targetRule) {
            if ($this->targetMatcher->matches($request, $context, $targetRule->getTarget())) {
                return $targetRule;
            }
        }
        return null;
    }
}