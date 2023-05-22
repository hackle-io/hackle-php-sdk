<?php

namespace Hackle\Internal\Evaluation\Match\Operator;

use Hackle\Internal\Model\MatchOperator;

final class OperatorMatcherFactory
{
    private $_inMatcher;
    private $_containsMatcher;
    private $_startsWithMatcher;
    private $_endsWithMatcher;
    private $_greaterThanMatcher;
    private $_greaterThanOrEqualToMatcher;
    private $_lessThanMatcher;
    private $_lessThanOrEqualToMatcher;

    public function __construct()
    {
        $this->_inMatcher = new InMatcher();
        $this->_containsMatcher = new ContainsMatcher();
        $this->_startsWithMatcher = new StartsWithMatcher();
        $this->_endsWithMatcher = new EndsWithMatcher();
        $this->_greaterThanMatcher = new GreaterThanMatcher();
        $this->_greaterThanOrEqualToMatcher = new GreaterThanOrEqualMatcher();
        $this->_lessThanMatcher = new LessThanMatcher();
        $this->_lessThanOrEqualToMatcher = new LessThanOrEqualMatcher();
    }

    public function getMatcher(MatchOperator $operator): OperatorMatcher
    {
        switch ($operator) {
            case MatchOperator::IN:
                return $this->_inMatcher;
            case MatchOperator::CONTAINS:
                return $this->_containsMatcher;
            case MatchOperator::STARTS_WITH:
                return $this->_startsWithMatcher;
            case MatchOperator::ENDS_WITH:
                return $this->_endsWithMatcher;
            case MatchOperator::GT:
                return $this->_greaterThanMatcher;
            case MatchOperator::GTE:
                return $this->_greaterThanOrEqualToMatcher;
            case MatchOperator::LT:
                return $this->_lessThanMatcher;
            case MatchOperator::LTE:
                return $this->_lessThanOrEqualToMatcher;
            default:
                throw new \InvalidArgumentException("Unsupported operator [$operator]");
        }
    }

}