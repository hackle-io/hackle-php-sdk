<?php

namespace Hackle\Internal\Evaluation\Match\Operator;

use Hackle\Internal\Model\Enums\Operator;

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

    public function getMatcher(Operator $operator): OperatorMatcher
    {
        switch ($operator) {
            case Operator::IN:
                return $this->_inMatcher;
            case Operator::CONTAINS:
                return $this->_containsMatcher;
            case Operator::STARTS_WITH:
                return $this->_startsWithMatcher;
            case Operator::ENDS_WITH:
                return $this->_endsWithMatcher;
            case Operator::GT:
                return $this->_greaterThanMatcher;
            case Operator::GTE:
                return $this->_greaterThanOrEqualToMatcher;
            case Operator::LT:
                return $this->_lessThanMatcher;
            case Operator::LTE:
                return $this->_lessThanOrEqualToMatcher;
            default:
                throw new \InvalidArgumentException("Unsupported operator [$operator]");
        }
    }

}