<?php

namespace Hackle\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use Hackle\Internal\Model\Enums\ExperimentStatus;
use Hackle\Internal\Model\Enums\ExperimentType;

final class PausedEvaluator implements FlowEvaluator
{
    public function evaluate(
        ExperimentRequest $request,
        EvaluatorContext $context,
        EvaluationFlow $nextFlow
    ): ExperimentEvaluation {
        if ($request->getExperiment()->getStatus()
            == ExperimentStatus::PAUSED
        ) {
            switch ($request->getExperiment()->getType()) {
                case ExperimentType::AB_TEST:
                    return ExperimentEvaluation::ofDefault(
                        $request,
                        $context,
                        DecisionReason::EXPERIMENT_PAUSED
                    );
                case ExperimentType::FEATURE_FLAG:
                    return ExperimentEvaluation::ofDefault(
                        $request,
                        $context,
                        DecisionReason::FEATURE_FLAG_INACTIVE
                    );
                default:
                    throw new \InvalidArgumentException(
                        "Unsupported experiment type[{$request->getExperiment()->getType()}]"
                    );
            }
        } else {
            return $nextFlow->evaluate($request, $context);
        }
    }
}
