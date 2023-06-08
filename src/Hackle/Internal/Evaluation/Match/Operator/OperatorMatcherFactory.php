<?php

namespace Hackle\Internal\Evaluation\Match\Operator;

use Hackle\Internal\Model\MatchOperator;

class OperatorMatcherFactory
{
    private $inMatcher;
    private $containsMatcher;
    private $startsWithMatcher;
    private $endsWithMatcher;
    private $greaterThanMatcher;
    private $greaterThanOrEqualToMatcher;
    private $lessThanMatcher;
    private $lessThanOrEqualToMatcher;

    public function __construct()
    {
        $this->inMatcher = new InMatcher();
        $this->containsMatcher = new ContainsMatcher();
        $this->startsWithMatcher = new StartsWithMatcher();
        $this->endsWithMatcher = new EndsWithMatcher();
        $this->greaterThanMatcher = new GreaterThanMatcher();
        $this->greaterThanOrEqualToMatcher = new GreaterThanOrEqualMatcher();
        $this->lessThanMatcher = new LessThanMatcher();
        $this->lessThanOrEqualToMatcher = new LessThanOrEqualMatcher();
    }

    public function getMatcher(MatchOperator $operator): OperatorMatcher
    {
        switch ($operator) {
            case MatchOperator::IN:
                return $this->inMatcher;
            case MatchOperator::CONTAINS:
                return $this->containsMatcher;
            case MatchOperator::STARTS_WITH:
                return $this->startsWithMatcher;
            case MatchOperator::ENDS_WITH:
                return $this->endsWithMatcher;
            case MatchOperator::GT:
                return $this->greaterThanMatcher;
            case MatchOperator::GTE:
                return $this->greaterThanOrEqualToMatcher;
            case MatchOperator::LT:
                return $this->lessThanMatcher;
            case MatchOperator::LTE:
                return $this->lessThanOrEqualToMatcher;
            default:
                throw new \InvalidArgumentException("Unsupported operator [$operator]");
        }
    }
}
