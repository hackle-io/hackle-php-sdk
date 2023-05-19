<?php

namespace Hackle\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;

final class IdentifierEvaluator implements FlowEvaluator
{

    public function evaluate(
        ExperimentRequest $request,
        EvaluatorContext $context,
        EvaluationFlow $nextFlow
    ): ExperimentEvaluation {
        if (array_key_exists($request->getExperiment()->getIdentifierType(), $request->getUser()->getIdentifiers())) {
            return $nextFlow->evaluate($request, $context);
        } else {
            return ExperimentEvaluation::ofDefault($request, $context, DecisionReason::IDENTIFIER_NOT_FOUND);
        }
    }
}