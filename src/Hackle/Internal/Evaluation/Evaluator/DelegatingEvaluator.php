<?php

namespace Hackle\Internal\Evaluation\Evaluator;

/**
 * @implements Evaluator<EvaluatorRequest, EvaluatorEvaluation>
 */
final class DelegatingEvaluator implements Evaluator
{
    /**
     * @var ContextualEvaluator[]
     */
    private $_evaluators;

    public function __construct()
    {
        $this->_evaluators = [];
    }

    public function add(ContextualEvaluator $evaluator)
    {
        $this->_evaluators[] = $evaluator;
    }

    function evaluate($request, EvaluatorContext $context)
    {
        foreach ($this->_evaluators as $evaluator) {
            if ($evaluator->supports($request)) {
                return $evaluator->evaluate($request, $context);
            }
        }
        throw new \InvalidArgumentException("Unsupported EvaluatorRequest [$request]");
    }
}