<?php

namespace Hackle\Internal\Evaluation\Match\Condition\User;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcher;
use Hackle\Internal\Evaluation\Match\Value\ValueOperatorMatcher;
use Hackle\Internal\Model\TargetCondition;

final class UserConditionMatcher implements ConditionMatcher
{
    private $_userValueResolver;
    private $_valueOperatorMatcher;

    public function __construct(UserValueResolver $_userValueResolver, ValueOperatorMatcher $_valueOperatorMatcher)
    {
        $this->_userValueResolver = $_userValueResolver;
        $this->_valueOperatorMatcher = $_valueOperatorMatcher;
    }


    function matches(EvaluatorRequest $request, EvaluatorContext $context, TargetCondition $condition): bool
    {
        $userValue = $this->_userValueResolver->resolveOrNull($request->getUser(), $condition->getKey());
        if ($userValue === null) {
            return false;
        }
        return $this->_valueOperatorMatcher->matches($userValue, $condition->getMatch());
    }
}