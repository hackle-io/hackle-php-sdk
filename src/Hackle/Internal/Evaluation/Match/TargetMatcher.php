<?php

namespace Hackle\Internal\Evaluation\Match;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcherFactory;
use Hackle\Internal\Model\Target;
use Hackle\Internal\Model\TargetCondition;

class TargetMatcher
{
    private $conditionMatcherFactory;

    public function __construct(ConditionMatcherFactory $conditionMatcherFactory)
    {
        $this->conditionMatcherFactory = $conditionMatcherFactory;
    }

    public function matches(EvaluatorRequest $request, EvaluatorContext $context, Target $target): bool
    {
        foreach ($target->getConditions() as $condition) {
            if (!$this->conditionMatches($request, $context, $condition)) {
                return false;
            }
        }
        return true;
    }

    private function conditionMatches(
        EvaluatorRequest $request,
        EvaluatorContext $context,
        TargetCondition $condition
    ): bool {
        $conditionMatcher = $this->conditionMatcherFactory->getMatcher($condition->getKey()->getType());
        return $conditionMatcher->matches($request, $context, $condition);
    }
}
