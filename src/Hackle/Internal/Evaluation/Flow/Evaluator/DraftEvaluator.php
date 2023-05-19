<?php

namespace Hackle\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use Hackle\Internal\Model\Enums\ExperimentStatus;

final class DraftEvaluator implements FlowEvaluator
{
    public function evaluate(
        ExperimentRequest $request,
        EvaluatorContext $context,
        EvaluationFlow $nextFlow
    ): ExperimentEvaluation {
        if ($request->getExperiment()->getStatus() == ExperimentStatus::DRAFT) {
            return ExperimentEvaluation::ofDefault($request, $context, DecisionReason::EXPERIMENT_DRAFT);
        } else {
            return $nextFlow->evaluate($request, $context);
        }
    }
}