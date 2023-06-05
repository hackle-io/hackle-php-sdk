<?php

namespace Hackle\Internal\Evaluation\Evaluator\Experiment;

use Hackle\Internal\Evaluation\Evaluator\ContextualEvaluator;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlowFactory;

/**
 * @template-extends  ContextualEvaluator<ExperimentRequest, ExperimentEvaluation>
 */
class ExperimentEvaluator extends ContextualEvaluator
{
    private $evaluationFlowFactory;

    public function __construct(EvaluationFlowFactory $evaluationFlowFactory)
    {
        $this->evaluationFlowFactory = $evaluationFlowFactory;
    }

    public function supports(EvaluatorRequest $request): bool
    {
        return $request instanceof ExperimentRequest;
    }

    protected function evaluateInternal($request, EvaluatorContext $context)
    {
        $evaluationFlow = $this->evaluationFlowFactory->getFlow($request->getExperiment()->getType());
        return $evaluationFlow->evaluate($request, $context);
    }
}
