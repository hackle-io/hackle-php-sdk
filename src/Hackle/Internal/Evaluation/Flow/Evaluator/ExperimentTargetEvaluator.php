<?php

namespace Hackle\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use Hackle\Internal\Evaluation\Target\ExperimentTargetDeterminer;
use Hackle\Internal\Model\Enums\ExperimentType;

use function Hackle\Internal\Lang\required;

final class ExperimentTargetEvaluator implements FlowEvaluator
{
    private $experimentTargetDeterminer;

    public function __construct(ExperimentTargetDeterminer $experimentTargetDeterminer)
    {
        $this->experimentTargetDeterminer = $experimentTargetDeterminer;
    }


    public function evaluate(
        ExperimentRequest $request,
        EvaluatorContext $context,
        EvaluationFlow $nextFlow
    ): ExperimentEvaluation {
        required(
            $request->getExperiment()->getType() == ExperimentType::AB_TEST,
            "experiment type must be AB_TEST [{$request->getExperiment()->getId()}]"
        );
        $isUserInExperimentTarget = $this->experimentTargetDeterminer->isUserInExperimentTarget($request, $context);
        if ($isUserInExperimentTarget) {
            return $nextFlow->evaluate($request, $context);
        } else {
            ExperimentEvaluation::ofDefault($request, $context, DecisionReason::NOT_IN_EXPERIMENT_TARGET);
        }
    }
}