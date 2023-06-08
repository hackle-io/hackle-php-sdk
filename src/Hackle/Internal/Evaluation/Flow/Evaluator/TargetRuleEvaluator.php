<?php

namespace Hackle\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Action\ActionResolver;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use Hackle\Internal\Evaluation\Target\ExperimentTargetRuleDeterminer;
use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Internal\Model\ExperimentType;

final class TargetRuleEvaluator implements FlowEvaluator
{
    private $targetRuleDeterminer;
    private $actionResolver;

    public function __construct(ExperimentTargetRuleDeterminer $targetRuleDeterminer, ActionResolver $actionResolver)
    {
        $this->targetRuleDeterminer = $targetRuleDeterminer;
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
            "experiment status must be RUNNING [{$experiment->getId()}]"
        );
        Objects::require(
            $experiment->getType() == ExperimentType::FEATURE_FLAG,
            "experiment type must be FEATURE_FLAG [{$experiment->getId()}]"
        );

        if (!array_key_exists($experiment->getIdentifierType(), $request->getUser()->getIdentifiers())) {
            return $nextFlow->evaluate($request, $context);
        }

        $targetRule = $this->targetRuleDeterminer->determineTargetRuleOrNull($request, $context);
        if ($targetRule === null) {
            return $nextFlow->evaluate($request, $context);
        }

        $variation = Objects::requireNotNull(
            $this->actionResolver->resolveOrNull($request, $targetRule->getAction()),
            "FeatureFlag must decide the variation [{$experiment->getId()}]"
        );

        return ExperimentEvaluation::of($request, $context, $variation, DecisionReason::TARGET_RULE_MATCH());
    }
}
