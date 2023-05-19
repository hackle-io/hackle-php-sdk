<?php

namespace Hackle\Internal\Evaluation\Match\Condition\Segment;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcher;
use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\Condition;
use Hackle\Internal\Model\Enums\KeyType;

use function Hackle\Internal\Lang\required;
use function Hackle\Internal\Lang\requireNotNull;

final class SegmentConditionMatcher implements ConditionMatcher
{
    private $_segmentMatcher;

    public function __construct(SegmentMatcher $_segmentMatcher)
    {
        $this->_segmentMatcher = $_segmentMatcher;
    }

    function matches(EvaluatorRequest $request, EvaluatorContext $context, Condition $condition): bool
    {
        required(
            $condition->getKey()->getType() == KeyType::SEGMENT,
            "Unsupported TargetKeyType [{$condition->getKey()->getType()}]"
        );
        $isMatched = $this->conditionMatches($request, $context, $condition);
        return $condition->getMatch()->getType()->matches($isMatched);
    }

    private function conditionMatches(EvaluatorRequest $request, EvaluatorContext $context, Condition $condition): bool
    {
        foreach ($condition->getMatch()->getValues() as $value) {
            if ($this->segmentMatches($request, $context, $value)) {
                return true;
            }
        }
        return false;
    }

    private function segmentMatches(EvaluatorRequest $request, EvaluatorContext $context, $value): bool
    {
        $segmentKey = requireNotNull(Objects::asStringOrNull($value), "SegmentKey[$value]");
        $segment = requireNotNull($request->getWorkspace()->getSegmentOrNull($segmentKey), "Segment[$segmentKey]");
        return $this->_segmentMatcher->matches($request, $context, $segment);
    }

}