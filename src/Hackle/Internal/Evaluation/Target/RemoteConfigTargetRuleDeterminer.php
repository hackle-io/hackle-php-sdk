<?php

namespace Hackle\Internal\Evaluation\Target;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigRequest;
use Hackle\Internal\Model\RemoteConfigTargetRule;

final class RemoteConfigTargetRuleDeterminer
{

    private $remoteConfigMatcher;

    public function __construct(RemoteConfigTargetMatcher $remoteConfigMatcher)
    {
        $this->remoteConfigMatcher = $remoteConfigMatcher;
    }

    public function determineTargetRuleOrNull(
        RemoteConfigRequest $request,
        EvaluatorContext $context
    ): ?RemoteConfigTargetRule {
        foreach ($request->getParameter()->getTargetRules() as $targetRule) {
            if ($this->remoteConfigMatcher->matches($request, $context, $targetRule)) {
                return $targetRule;
            }
        }
        return null;
    }
}
