<?php

namespace Hackle\Internal\Evaluation\Flow\Evaluator;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;

interface FlowEvaluator
{
    public function evaluate(
        ExperimentRequest $request,
        EvaluatorContext $context,
        EvaluationFlow $nextFlow
    ): ExperimentEvaluation;
}
