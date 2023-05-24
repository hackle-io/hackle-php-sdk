<?php

namespace Hackle\Internal\Evaluation\Evaluator;

/**
 * @template REQUEST of EvaluatorRequest
 * @template EVALUATION of EvaluatorEvaluation
 *
 * @implements Evaluator<REQUEST, EVALUATION>
 */
abstract class ContextualEvaluator implements Evaluator
{

    abstract public function supports(EvaluatorRequest $request): bool;

    /**
     * @param REQUEST $request
     * @param EvaluatorContext $context
     * @return EVALUATION
     */
    abstract protected function evaluateInternal($request, EvaluatorContext $context);

    public function evaluate($request, EvaluatorContext $context)
    {
        if ($context->contains($request)) {
            throw new \InvalidArgumentException(
                "Circular evaluation has occurred [" . implode(" - ", $context->getStack()) . " - " . $request . "]"
            );
        }
        $context->push($request);
        try {
            return $this->evaluateInternal($request, $context);
        } finally {
            $context->pop();
        }
    }
}
