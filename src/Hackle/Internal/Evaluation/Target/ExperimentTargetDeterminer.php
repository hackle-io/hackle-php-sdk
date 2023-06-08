<?php

namespace Hackle\Internal\Evaluation\Target;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Match\TargetMatcher;

class ExperimentTargetDeterminer
{
    private $targetMatcher;

    public function __construct(TargetMatcher $targetMatcher)
    {
        $this->targetMatcher = $targetMatcher;
    }

    public function isUserInExperimentTarget(ExperimentRequest $request, EvaluatorContext $context): bool
    {
        if (empty($request->getExperiment()->getTargetAudiences())) {
            return true;
        }
        foreach ($request->getExperiment()->getTargetAudiences() as $targetAudience) {
            if ($this->targetMatcher->matches($request, $context, $targetAudience)) {
                return true;
            }
        }
        return false;
    }
}
