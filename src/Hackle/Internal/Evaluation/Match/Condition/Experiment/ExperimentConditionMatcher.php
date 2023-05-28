<?php

namespace Hackle\Internal\Evaluation\Match\Condition\Experiment;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcher;
use Hackle\Internal\Model\TargetCondition;
use Hackle\Internal\Model\TargetKeyType;

class ExperimentConditionMatcher implements ConditionMatcher
{
    private $abTestMatcher;
    private $featureFlagMatcher;

    public function __construct(AbTestConditionMatcher $abTestMatcher, FeatureFlagConditionMatcher $featureFlagMatcher)
    {
        $this->abTestMatcher = $abTestMatcher;
        $this->featureFlagMatcher = $featureFlagMatcher;
    }

    public function matches(EvaluatorRequest $request, EvaluatorContext $context, TargetCondition $condition): bool
    {
        switch ($condition->getKey()->getType()) {
            case TargetKeyType::AB_TEST:
                return $this->abTestMatcher->matches($request, $context, $condition);
            case TargetKeyType::FEATURE_FLAG:
                return $this->featureFlagMatcher->matches($request, $context, $condition);
            default:
                throw new \InvalidArgumentException("Unsupported TargetKeyType [{$condition->getKey()->getType()}]");
        }
    }
}
