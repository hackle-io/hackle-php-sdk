<?php

namespace Hackle\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\ExperimentStatus;

final class CompletedEvaluator implements FlowEvaluator
{

    public function evaluate(
        ExperimentRequest $request,
        EvaluatorContext $context,
        EvaluationFlow $nextFlow
    ): ExperimentEvaluation {
        if ($request->getExperiment()->getStatus() == ExperimentStatus::COMPLETED) {
            $winnerVariation = Objects::requireNotNull(
                $request->getExperiment()->getWinnerVariation(),
                "Winner variation [{$request->getExperiment()->getId()}]"
            );

            return ExperimentEvaluation::of(
                $request,
                $context,
                $winnerVariation,
                DecisionReason::EXPERIMENT_COMPLETED()
            );
        } else {
            return $nextFlow->evaluate($request, $context);
        }
    }
}