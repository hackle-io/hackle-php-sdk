<?php

namespace Hackle\Internal\Evaluation\Evaluator;


/**
 * @template REQUEST of EvaluatorRequest
 * @template EVALUATION of EvaluatorEvaluation
 */
interface Evaluator
{
    /**
     * @param REQUEST $request
     * @param EvaluatorContext $context
     * @return EVALUATION
     */
    function evaluate($request, EvaluatorContext $context);
}