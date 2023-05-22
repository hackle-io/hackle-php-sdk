<?php

namespace Hackle\Internal\Evaluation\Match;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcherFactory;
use Hackle\Internal\Model\Target;
use Hackle\Internal\Model\TargetCondition;

final class TargetMatcher
{
    private $_conditionMatcherFactory;

    public function __construct(ConditionMatcherFactory $_conditionMatcherFactory)
    {
        $this->_conditionMatcherFactory = $_conditionMatcherFactory;
    }

    function matches(EvaluatorRequest $request, EvaluatorContext $context, Target $target): bool
    {
        foreach ($target->getConditions() as $condition) {
            if (!$this->condition_matches($request, $context, $condition)) {
                return false;
            }
        }
        return true;
    }

    private function condition_matches(
        EvaluatorRequest $request,
        EvaluatorContext $context,
        TargetCondition $condition
    ): bool {
        $conditionMatcher = $this->_conditionMatcherFactory->getMatcher($condition->getKey()->getType());
        return $conditionMatcher->matches($request, $context, $condition);
    }
}