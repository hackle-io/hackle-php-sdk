<?php

namespace Hackle\Internal\Evaluation\Evaluator;

/**
 * @implements Evaluator<EvaluatorRequest, EvaluatorEvaluation>
 */
class DelegatingEvaluator implements Evaluator
{
    /**
     * @var ContextualEvaluator[]
     */
    private $evaluators;

    public function __construct()
    {
        $this->evaluators = [];
    }

    public function add(ContextualEvaluator $evaluator)
    {
        $this->evaluators[] = $evaluator;
    }

    public function evaluate($request, EvaluatorContext $context)
    {
        foreach ($this->evaluators as $evaluator) {
            if ($evaluator->supports($request)) {
                return $evaluator->evaluate($request, $context);
            }
        }
        throw new \InvalidArgumentException("Unsupported EvaluatorRequest [$request]");
    }
}
