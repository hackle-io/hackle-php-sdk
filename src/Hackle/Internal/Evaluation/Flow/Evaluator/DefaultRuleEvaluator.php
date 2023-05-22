<?php

namespace Hackle\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Action\ActionResolver;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\Enums\ExperimentStatus;
use Hackle\Internal\Model\Enums\ExperimentType;

final class DefaultRuleEvaluator implements FlowEvaluator
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

        Objects::require(
            $experiment->getStatus() == ExperimentStatus::RUNNING,
            "experiment status must be RUNNING [{$experiment->getId()}"
        );
        Objects::require(
            $experiment->getType() == ExperimentType::AB_TEST,
            "experiment type must be FEATURE_FLAG [{$experiment->getId()}"
        );

        if (!array_key_exists($experiment->getIdentifierType(), $request->getUser()->getIdentifiers())) {
            return ExperimentEvaluation::ofDefault($request, $context, DecisionReason::DEFAULT_RULE());
        }

        $variation = Objects::requireNotNull(
            $this->actionResolver->resolveOrNull($request, $experiment->getDefaultRule()),
            "FeatureFlag must decide the Variation [{$experiment->getId()}]"
        );

        return ExperimentEvaluation::of($request, $context, $variation, DecisionReason::DEFAULT_RULE());
    }
}