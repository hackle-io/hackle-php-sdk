<?php

namespace Hackle\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Container\ContainerResolver;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;

use function Hackle\Internal\Lang\requireNotNull;

final class ContainerEvaluator implements FlowEvaluator
{
    private $containerResolver;

    public function __construct(ContainerResolver $containerResolver)
    {
        $this->containerResolver = $containerResolver;
    }


    public function evaluate(
        ExperimentRequest $request,
        EvaluatorContext $context,
        EvaluationFlow $nextFlow
    ): ExperimentEvaluation {
        $containerId = $request->getExperiment()->getContainerId();
        if ($containerId === null) {
            return $nextFlow->evaluate($request, $context);
        }
        $container = requireNotNull(
            $request->getWorkspace()->getContainerOrNull($containerId),
            "Container[{$containerId}]"
        );
        if ($this->containerResolver->isUserInContainerGroup($request, $container)) {
            return $nextFlow->evaluate($request, $context);
        } else {
            return ExperimentEvaluation::ofDefault(
                $request,
                $context,
                DecisionReason::NOT_IN_MUTUAL_EXCLUSION_EXPERIMENT
            );
        }
    }
}