<?php

namespace Hackle\Internal\Evaluation\Match\Condition;

use Hackle\Internal\Evaluation\Evaluator\Evaluator;
use Hackle\Internal\Evaluation\Match\Condition\Experiment\AbTestConditionMatcher;
use Hackle\Internal\Evaluation\Match\Condition\Experiment\ExperimentConditionMatcher;
use Hackle\Internal\Evaluation\Match\Condition\Experiment\FeatureFlagConditionMatcher;
use Hackle\Internal\Evaluation\Match\Condition\Segment\SegmentConditionMatcher;
use Hackle\Internal\Evaluation\Match\Condition\Segment\SegmentMatcher;
use Hackle\Internal\Evaluation\Match\Condition\User\UserConditionMatcher;
use Hackle\Internal\Evaluation\Match\Condition\User\UserValueResolver;
use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcherFactory;
use Hackle\Internal\Evaluation\Match\Value\ValueMatcherFactory;
use Hackle\Internal\Evaluation\Match\Value\ValueOperatorMatcher;
use Hackle\Internal\Model\TargetKeyType;

final class ConditionMatcherFactory
{
    private $userConditionMatcher;
    private $segmentConditionMatcher;
    private $experimentConditionMatcher;

    public function __construct(Evaluator $evaluator)
    {
        $valueOperatorMatcher = new ValueOperatorMatcher(new ValueMatcherFactory(), new OperatorMatcherFactory());
        $this->userConditionMatcher = new UserConditionMatcher(new UserValueResolver(), $valueOperatorMatcher);
        $this->segmentConditionMatcher = new SegmentConditionMatcher(new SegmentMatcher($this->userConditionMatcher));
        $this->experimentConditionMatcher = new ExperimentConditionMatcher(
            new AbTestConditionMatcher($evaluator, $valueOperatorMatcher),
            new FeatureFlagConditionMatcher($evaluator, $valueOperatorMatcher)
        );
    }

    public function getMatcher(TargetKeyType $type): ConditionMatcher
    {
        switch ($type) {
            case TargetKeyType::USER_ID:
            case TargetKeyType::USER_PROPERTY:
            case TargetKeyType::HACKLE_PROPERTY:
                return $this->userConditionMatcher;
            case TargetKeyType::SEGMENT:
                return $this->segmentConditionMatcher;
            case TargetKeyType::AB_TEST:
            case TargetKeyType::FEATURE_FLAG:
                return $this->experimentConditionMatcher;
            default:
                throw new \InvalidArgumentException("Unsupported TargetKeyType [$type]");
        }
    }
}
