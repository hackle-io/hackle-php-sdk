<?php

namespace Hackle\Tests\Internal\Evaluation\Evaluator;

use Hackle\Internal\Evaluation\Evaluator\ContextualEvaluator;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorEvaluation;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;

/**
 * @template-extends  ContextualEvaluator<EvaluatorRequest, EvaluatorEvaluation>
 */
class MockEvaluator extends ContextualEvaluator
{
    private $request;
    private $evaluation;

    public function __construct(EvaluatorRequest $request, EvaluatorEvaluation $evaluation)
    {
        $this->request = $request;
        $this->evaluation = $evaluation;
    }

    public function supports(EvaluatorRequest $request): bool
    {
        return $this->request === $request;
    }

    protected function evaluateInternal($request, EvaluatorContext $context)
    {
        return $this->evaluation;
    }
}
