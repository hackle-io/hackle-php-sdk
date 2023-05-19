<?php

namespace Hackle\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Action\ActionResolver;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use Hackle\Internal\Model\Enums\ExperimentStatus;
use Hackle\Internal\Model\Enums\ExperimentType;

use function Hackle\Internal\Lang\required;

final class TrafficAllocateEvaluator implements FlowEvaluator
{
    private $actionResolver;

    public function __construct(ActionResolver $actionResolver)
    {
        $this->actionResolver = $actionResolver;
    }

    public function evaluate(
        ExperimentRequest $request,
        EvaluatorContext $context,
        EvaluationFlow $nextFlow
    ): ExperimentEvaluation {
        $experiment = $request->getExperiment();

        required(
            $experiment->getStatus() == ExperimentStatus::RUNNING,
            "experiment status must be RUNNING [{$experiment->getId()}"
        );
        required(
            $experiment->getType() == ExperimentType::AB_TEST,
            "experiment type must be AB_TEST [{$experiment->getId()}"
        );

        $defaultRule = $experiment->getDefaultRule();
        $variation = $this->actionResolver->resolveOrNull($request, $defaultRule);
        if ($variation === null) {
            return ExperimentEvaluation::ofDefault($request, $context, DecisionReason::TRAFFIC_NOT_ALLOCATED);
        }

        if ($variation->isIsDropped()) {
            return ExperimentEvaluation::ofDefault($request, $context, DecisionReason::VARIATION_DROPPED);
        }

        return ExperimentEvaluation::of($request, $context, $variation, DecisionReason::TRAFFIC_ALLOCATED);
    }
}