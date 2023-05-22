<?php

namespace Hackle\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcher;
use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcherFactory;
use Hackle\Internal\Model\TargetMatch;

final class ValueOperatorMatcher
{
    private $_valueMatcherFactory;
    private $_operatorMatcherFactory;

    public function __construct(
        ValueMatcherFactory $_valueMatcherFactory,
        OperatorMatcherFactory $_operatorMatcherFactory
    ) {
        $this->_valueMatcherFactory = $_valueMatcherFactory;
        $this->_operatorMatcherFactory = $_operatorMatcherFactory;
    }

    public function matches($userValue, TargetMatch $match): bool
    {
        $valueMatcher = $this->_valueMatcherFactory->getMatcher($match->getValueType());
        $operatorMatcher = $this->_operatorMatcherFactory->getMatcher($match->getOperator());

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