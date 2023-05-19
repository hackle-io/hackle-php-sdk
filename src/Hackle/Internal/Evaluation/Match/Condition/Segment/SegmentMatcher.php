<?php

namespace Hackle\Internal\Evaluation\Match\Condition\Segment;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\User\UserConditionMatcher;
use Hackle\Internal\Model\Segment;
use Hackle\Internal\Model\Target;

final class SegmentMatcher
{
    private $_userConditionMatcher;

    public function __construct(UserConditionMatcher $_userConditionMatcher)
    {
        $this->_userConditionMatcher = $_userConditionMatcher;
    }

    public function matches(EvaluatorRequest $request, EvaluatorContext $context, Segment $segment): bool
    {
        foreach ($segment->getTargets() as $target) {
            if ($this->targetMatches($request, $context, $target)) {
                return true;
            }
        }
        return false;
    }

    private function targetMatches(EvaluatorRequest $request, EvaluatorContext $context, Target $target): bool
    {
        foreach ($target->getConditions() as $condition) {
            if (!$this->_userConditionMatcher->matches($request, $context, $condition)) {
                return false;
            }
        }
        return true;
    }
}