<?php

namespace Hackle\Internal\Evaluation\Flow;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Flow\Evaluator\FlowEvaluator;

class EvaluationFlow
{
    private $flowEvaluator;
    private $nextFlow;

    public function __construct(?FlowEvaluator $flowEvaluator, ?EvaluationFlow $nextFlow)
    {
        $this->flowEvaluator = $flowEvaluator;
        $this->nextFlow = $nextFlow;
    }

    public static function of(...$evaluators): EvaluationFlow
    {
        $flow = new EvaluationFlow(null, null);
        foreach (array_reverse($evaluators) as $evaluator) {
            $flow = new EvaluationFlow($evaluator, $flow);
        }
        return $flow;
    }

    public function evaluate(ExperimentRequest $request, EvaluatorContext $context): ExperimentEvaluation
    {
        return $this->isEnd()
            ? ExperimentEvaluation::ofDefault($request, $context, DecisionReason::TRAFFIC_NOT_ALLOCATED())
            : $this->flowEvaluator->evaluate($request, $context, $this->nextFlow);
    }

    public function isEnd(): bool
    {
        return $this->flowEvaluator === null || $this->nextFlow === null;
    }
}
