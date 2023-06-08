<?php

namespace Hackle\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcher;
use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcherFactory;
use Hackle\Internal\Model\TargetMatch;

class ValueOperatorMatcher
{
    private $valueMatcherFactory;
    private $operatorMatcherFactory;

    public function __construct(
        ValueMatcherFactory $valueMatcherFactory,
        OperatorMatcherFactory $operatorMatcherFactory
    ) {
        $this->valueMatcherFactory = $valueMatcherFactory;
        $this->operatorMatcherFactory = $operatorMatcherFactory;
    }

    public function matches($userValue, TargetMatch $match): bool
    {
        $valueMatcher = $this->valueMatcherFactory->getMatcher($match->getValueType());
        $operatorMatcher = $this->operatorMatcherFactory->getMatcher($match->getOperator());

        if (is_array($userValue)) {
            $isMatched = $this->arrayMatches($userValue, $match, $valueMatcher, $operatorMatcher);
        } else {
            $isMatched = $this->singleMatches($userValue, $match, $valueMatcher, $operatorMatcher);
        }
        return $match->getType()->matches($isMatched);
    }

    private function singleMatches(
        $userValue,
        TargetMatch $match,
        ValueMatcher $valueMatcher,
        OperatorMatcher $operatorMatcher
    ): bool {
        foreach ($match->getValues() as $matchValue) {
            if ($valueMatcher->matches($operatorMatcher, $userValue, $matchValue)) {
                return true;
            }
        }
        return false;
    }


    private function arrayMatches(
        array $userValues,
        TargetMatch $match,
        ValueMatcher $valueMatcher,
        OperatorMatcher $operatorMatcher
    ): bool {
        foreach ($userValues as $userValue) {
            if ($this->singleMatches($userValue, $match, $valueMatcher, $operatorMatcher)) {
                return true;
            }
        }
        return false;
    }
}
