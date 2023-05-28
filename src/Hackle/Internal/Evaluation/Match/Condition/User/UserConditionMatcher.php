<?php

namespace Hackle\Internal\Evaluation\Match\Condition\User;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcher;
use Hackle\Internal\Evaluation\Match\Value\ValueOperatorMatcher;
use Hackle\Internal\Model\TargetCondition;

class UserConditionMatcher implements ConditionMatcher
{
    private $userValueResolver;
    private $valueOperatorMatcher;

    public function __construct(UserValueResolver $userValueResolver, ValueOperatorMatcher $valueOperatorMatcher)
    {
        $this->userValueResolver = $userValueResolver;
        $this->valueOperatorMatcher = $valueOperatorMatcher;
    }

    public function matches(EvaluatorRequest $request, EvaluatorContext $context, TargetCondition $condition): bool
    {
        $userValue = $this->userValueResolver->resolveOrNull($request->getUser(), $condition->getKey());
        if ($userValue === null) {
            return false;
        }
        return $this->valueOperatorMatcher->matches($userValue, $condition->getMatch());
    }
}
