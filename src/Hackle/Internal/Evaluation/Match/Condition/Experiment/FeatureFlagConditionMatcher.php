<?php

namespace Hackle\Internal\Evaluation\Match\Condition\Experiment;

use Hackle\Internal\Evaluation\Evaluator\Evaluator;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Match\Value\ValueOperatorMatcher;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\Model\TargetCondition;

class FeatureFlagConditionMatcher extends AbstractExperimentMatcher
{
    public function __construct(Evaluator $evaluator, ValueOperatorMatcher $valueOperatorMatcher)
    {
        parent::__construct($evaluator, $valueOperatorMatcher);
    }

    protected function experiment(EvaluatorRequest $request, int $key): ?Experiment
    {
        return $request->getWorkspace()->getFeatureFlagOrNull($key);
    }

    protected function resolve(EvaluatorRequest $request, ExperimentEvaluation $evaluation): ExperimentEvaluation
    {
        return $evaluation;
    }

    protected function evaluationMatches(ExperimentEvaluation $evaluation, TargetCondition $condition): bool
    {
        $isOn = $evaluation->getVariationKey() !== "A";
        return $this->valueOperatorMatcher->matches($isOn, $condition->getMatch());
    }
}
