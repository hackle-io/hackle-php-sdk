<?php

namespace Hackle\Internal\Evaluation\Match\Condition\Experiment;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcher;
use Hackle\Internal\Model\Condition;
use Hackle\Internal\Model\Enums\KeyType;

final class ExperimentConditionMatcher implements ConditionMatcher
{
    private $abTestMatcher;
    private $featureFlagMatcher;

    public function __construct(AbTestConditionMatcher $abTestMatcher, FeatureFlagConditionMatcher $featureFlagMatcher)
    {
        $this->abTestMatcher = $abTestMatcher;
        $this->featureFlagMatcher = $featureFlagMatcher;
    }

    function matches(EvaluatorRequest $request, EvaluatorContext $context, Condition $condition): bool
    {
        switch ($condition->getKey()->getType()) {
            case KeyType::AB_TEST:
                return $this->abTestMatcher->matches($request, $context, $condition);
            case KeyType::FEATURE_FLAG:
                return $this->featureFlagMatcher->matches($request, $context, $condition);
            default:
                throw new \InvalidArgumentException("Unsupported TargetKetType [{$condition->getKey()->getType()}]");
        }
    }
}