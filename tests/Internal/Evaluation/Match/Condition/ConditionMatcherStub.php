<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Condition;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcher;
use Hackle\Internal\Model\TargetCondition;

class ConditionMatcherStub implements ConditionMatcher
{

    private $returns = [];
    private $callCount = 0;

    public function addReturn(bool $value)
    {
        $this->returns[] = $value;
    }

    public function matches(EvaluatorRequest $request, EvaluatorContext $context, TargetCondition $condition): bool
    {
        return $this->returns[$this->callCount++];
    }

    /**
     * @return int
     */
    public function getCallCount(): int
    {
        return $this->callCount;
    }
}
