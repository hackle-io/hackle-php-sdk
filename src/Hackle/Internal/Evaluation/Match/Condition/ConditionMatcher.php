<?php

namespace Hackle\Internal\Evaluation\Match\Condition;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Model\Condition;

interface ConditionMatcher
{
    function matches(EvaluatorRequest $request, EvaluatorContext $context, Condition $condition): bool;
}