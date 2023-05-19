<?php

namespace Hackle\Internal\Evaluation\Match\Condition\Experiment;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\Evaluator;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Match\Value\ValueOperatorMatcher;
use Hackle\Internal\Model\Condition;
use Hackle\Internal\Model\Experiment;

final class AbTestConditionMatcher extends AbstractExperimentMatcher
{
    private const AB_TEST_MATCHED_REASONS = [
        DecisionReason::OVERRIDDEN,
        DecisionReason::TRAFFIC_ALLOCATED,
        DecisionReason::TRAFFIC_ALLOCATED_BY_TARGETING,
        DecisionReason::EXPERIMENT_COMPLETED
    ];

    public function __construct(Evaluator $evaluator, ValueOperatorMatcher $valueOperatorMatcher)
    {
        parent::__construct($evaluator, $valueOperatorMatcher);
    }

    protected function experiment(EvaluatorRequest $request, int $key): ?Experiment
    {
        return $request->getWorkspace()->getExperimentOrNull($key);
    }

    protected function resolve(EvaluatorRequest $request, ExperimentEvaluation $evaluation): ExperimentEvaluation
    {
        if ($request instanceof ExperimentRequest && $evaluation->getReason() == DecisionReason::TRAFFIC_ALLOCATED) {
            return $evaluation->with(new DecisionReason(DecisionReason::TRAFFIC_ALLOCATED_BY_TARGETING));
        }
        return $evaluation;
    }

    protected function evaluationMatches(ExperimentEvaluation $evaluation, Condition $condition): bool
    {
        if (in_array($evaluation->getReason(), self::AB_TEST_MATCHED_REASONS)) {
            return false;
        }

        return $this->valueOperatorMatcher->matches($evaluation->getVariationKey(), $condition->getMatch());
    }
}
