<?php

namespace Hackle\Internal\Evaluation\Target;

use Hackle\Internal\Evaluation\Bucket\Bucketer;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigRequest;
use Hackle\Internal\Evaluation\Match\TargetMatcher;
use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\RemoteConfigTargetRule;

final class RemoteConfigTargetMatcher
{
    private $targetMatcher;
    private $bucketer;

    public function __construct(TargetMatcher $targetMatcher, Bucketer $bucketer)
    {
        $this->targetMatcher = $targetMatcher;
        $this->bucketer = $bucketer;
    }

    public function matches(
        RemoteConfigRequest $request,
        EvaluatorContext $context,
        RemoteConfigTargetRule $targetRule
    ): bool {
        if (!$this->targetMatcher->matches($request, $context, $targetRule->getTarget())) {
            return false;
        }

        $identifier = $request->getUser()->getIdentifiers()[$request->getParameter()->getIdentifierType()] ?? null;
        if ($identifier === null) {
            return false;
        }

        $bucket = Objects::requireNotNull(
            $request->getWorkspace()->getBucketOrNull($targetRule->getBucketId()),
            "Bucket[{$targetRule->getBucketId()}]"
        );
        return $this->bucketer->bucketing($bucket, $identifier) !== null;
    }
}