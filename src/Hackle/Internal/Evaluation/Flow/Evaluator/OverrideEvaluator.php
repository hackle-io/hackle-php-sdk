<?php

namespace Hackle\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use Hackle\Internal\Evaluation\Target\OverrideResolver;
use Hackle\Internal\Model\Enums\ExperimentType;

final class OverrideEvaluator implements FlowEvaluator
{

    private $overrideResolver;

    public function __construct(OverrideResolver $overrideResolver)
    {
        $this->overrideResolver = $overrideResolver;
    }


    public function evaluate(
        ExperimentRequest $request,
        EvaluatorContext $context,
        EvaluationFlow $nextFlow
    ): ExperimentEvaluation {
        $overriddenVariation = $this->overrideResolver->resolveOrNull(
            $request,
            $context
        );
        if ($overriddenVariation !== null) {
            switch ($request->getExperiment()->getType()) {
                case ExperimentType::AB_TEST:
                    return ExperimentEvaluation::of(
                        $request,
                        $context,
                        $overriddenVariation,
                        DecisionReason::OVERRIDDEN()
                    );
                case ExperimentType::FEATURE_FLAG:
                    return ExperimentEvaluation::of(
                        $request,
                        $context,
                        $overriddenVariation,
                        DecisionReason::INDIVIDUAL_TARGET_MATCH()
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