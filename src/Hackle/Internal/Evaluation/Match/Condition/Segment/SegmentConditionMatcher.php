<?php

namespace Hackle\Internal\Evaluation\Match\Condition\Segment;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcher;
use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\TargetCondition;
use Hackle\Internal\Model\TargetKeyType;

class SegmentConditionMatcher implements ConditionMatcher
{
    private $segmentMatcher;

    public function __construct(SegmentMatcher $segmentMatcher)
    {
        $this->segmentMatcher = $segmentMatcher;
    }

    public function matches(EvaluatorRequest $request, EvaluatorContext $context, TargetCondition $condition): bool
    {
        Objects::require(
            $condition->getKey()->getType() == TargetKeyType::SEGMENT,
            "Unsupported TargetKeyType [{$condition->getKey()->getType()}]"
        );
        $isMatched = $this->conditionMatches($request, $context, $condition);
        return $condition->getMatch()->getType()->matches($isMatched);
    }

    private function conditionMatches(
        EvaluatorRequest $request,
        EvaluatorContext $context,
        TargetCondition $condition
    ): bool {
        foreach ($condition->getMatch()->getValues() as $value) {
            if ($this->segmentMatches($request, $context, $value)) {
                return true;
            }
        }
        return false;
    }

    private function segmentMatches(EvaluatorRequest $request, EvaluatorContext $context, $value): bool
    {
        Objects::require(is_string($value), "SegmentKey[$value]");
        $segment = Objects::requireNotNull(
            $request->getWorkspace()->getSegmentOrNull($value),
            "Segment[$value]"
        );
        return $this->segmentMatcher->matches($request, $context, $segment);
    }
}
