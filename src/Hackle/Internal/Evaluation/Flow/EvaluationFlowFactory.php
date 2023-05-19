<?php

namespace Hackle\Internal\Evaluation\Flow;

use Hackle\Internal\Evaluation\Action\ActionResolver;
use Hackle\Internal\Evaluation\Bucket\Bucketer;
use Hackle\Internal\Evaluation\Bucket\Murmur3Hash;
use Hackle\Internal\Evaluation\Container\ContainerResolver;
use Hackle\Internal\Evaluation\Evaluator\Evaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\CompletedEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\ContainerEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\DefaultRuleEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\DraftEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\ExperimentTargetEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\IdentifierEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\OverrideEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\PausedEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\TargetRuleEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\TrafficAllocateEvaluator;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcherFactory;
use Hackle\Internal\Evaluation\Match\TargetMatcher;
use Hackle\Internal\Evaluation\Target\ExperimentTargetDeterminer;
use Hackle\Internal\Evaluation\Target\ExperimentTargetRuleDeterminer;
use Hackle\Internal\Evaluation\Target\OverrideResolver;
use Hackle\Internal\Evaluation\Target\RemoteConfigTargetMatcher;
use Hackle\Internal\Evaluation\Target\RemoteConfigTargetRuleDeterminer;
use Hackle\Internal\Model\Enums\ExperimentType;

final class EvaluationFlowFactory
{

    private $abTestFlow;
    private $featureFlagFlow;
    private $remoteConfigTargetRuleDeterminer;

    public function __construct(Evaluator $evaluator)
    {
        $bucketer = new Bucketer(new Murmur3Hash());
        $targetMatcher = new TargetMatcher(new ConditionMatcherFactory($evaluator));
        $actionResolver = new ActionResolver($bucketer);
        $overrideResolver = new OverrideResolver($targetMatcher, $actionResolver);
        $containerResolver = new ContainerResolver($bucketer);

        $this->abTestFlow = EvaluationFlow::of(
            new OverrideEvaluator($overrideResolver),
            new IdentifierEvaluator(),
            new ContainerEvaluator($containerResolver),
            new ExperimentTargetEvaluator(new ExperimentTargetDeterminer($targetMatcher)),
            new DraftEvaluator(),
            new PausedEvaluator(),
            new CompletedEvaluator(),
            new TrafficAllocateEvaluator($actionResolver)
        );

        $this->featureFlagFlow = EvaluationFlow::of(
            new DraftEvaluator(),
            new PausedEvaluator(),
            new CompletedEvaluator(),
            new OverrideEvaluator($overrideResolver),
            new IdentifierEvaluator(),
            new TargetRuleEvaluator(new ExperimentTargetRuleDeterminer($targetMatcher), $actionResolver),
            new DefaultRuleEvaluator($actionResolver)
        );

        $this->remoteConfigTargetRuleDeterminer = new RemoteConfigTargetRuleDeterminer(
            new RemoteConfigTargetMatcher($targetMatcher, $bucketer)
        );
    }


    public function getFlow(ExperimentType $experimentType): EvaluationFlow
    {
        switch ($experimentType) {
            case ExperimentType::AB_TEST:
                return $this->abTestFlow;
            case ExperimentType::FEATURE_FLAG:
                return $this->featureFlagFlow;
            default:
                throw new \InvalidArgumentException("Unsupported experimentType [{$experimentType}]");
        }
    }

    /**
     * @return RemoteConfigTargetRuleDeterminer
     */
    public function getRemoteConfigTargetRuleDeterminer(): RemoteConfigTargetRuleDeterminer
    {
        return $this->remoteConfigTargetRuleDeterminer;
    }
}