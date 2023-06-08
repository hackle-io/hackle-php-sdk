<?php

namespace Hackle\Internal\Evaluation\Match\Condition\Segment;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\User\UserConditionMatcher;
use Hackle\Internal\Model\Segment;
use Hackle\Internal\Model\Target;

class SegmentMatcher
{
    private $userConditionMatcher;

    public function __construct(UserConditionMatcher $userConditionMatcher)
    {
        $this->userConditionMatcher = $userConditionMatcher;
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
            if (!$this->userConditionMatcher->matches($request, $context, $condition)) {
                return false;
            }
        }
        return true;
    }
}
