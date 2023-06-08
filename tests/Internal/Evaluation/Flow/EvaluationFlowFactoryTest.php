<?php

namespace Hackle\Tests\Internal\Evaluation\Flow;

use Hackle\Internal\Evaluation\Evaluator\Evaluator;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use Hackle\Internal\Evaluation\Flow\EvaluationFlowFactory;
use Hackle\Internal\Evaluation\Flow\Evaluator\CompletedEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\ContainerEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\DefaultRuleEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\DraftEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\ExperimentTargetEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\FlowEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\IdentifierEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\OverrideEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\PausedEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\TargetRuleEvaluator;
use Hackle\Internal\Evaluation\Flow\Evaluator\TrafficAllocateEvaluator;
use Hackle\Internal\Model\ExperimentType;
use PHPUnit\Framework\TestCase;

class EvaluationFlowFactoryTest extends TestCase
{

    public function test__AB_TEST()
    {
        $sut = new EvaluationFlowFactory($this->createMock(Evaluator::class));

        $flow = $sut->getFlow(ExperimentType::AB_TEST());

        $flow = $this->isDecisionWith($flow, OverrideEvaluator::class);
        $flow = $this->isDecisionWith($flow, IdentifierEvaluator::class);
        $flow = $this->isDecisionWith($flow, ContainerEvaluator::class);
        $flow = $this->isDecisionWith($flow, ExperimentTargetEvaluator::class);
        $flow = $this->isDecisionWith($flow, DraftEvaluator::class);
        $flow = $this->isDecisionWith($flow, PausedEvaluator::class);
        $flow = $this->isDecisionWith($flow, CompletedEvaluator::class);
        $flow = $this->isDecisionWith($flow, TrafficAllocateEvaluator::class);
        self::assertTrue($flow->isEnd());
    }

    public function test__FEATURE_FLAG()
    {
        $sut = new EvaluationFlowFactory($this->createMock(Evaluator::class));

        $flow = $sut->getFlow(ExperimentType::FEATURE_FLAG());

        $flow = $this->isDecisionWith($flow, DraftEvaluator::class);
        $flow = $this->isDecisionWith($flow, PausedEvaluator::class);
        $flow = $this->isDecisionWith($flow, CompletedEvaluator::class);
        $flow = $this->isDecisionWith($flow, OverrideEvaluator::class);
        $flow = $this->isDecisionWith($flow, IdentifierEvaluator::class);
        $flow = $this->isDecisionWith($flow, TargetRuleEvaluator::class);
        $flow = $this->isDecisionWith($flow, DefaultRuleEvaluator::class);
        self::assertTrue($flow->isEnd());
    }

    /**
     * @template T of FlowEvaluator
     *
     * @param EvaluationFlow $evaluationFlow
     * @param class-string<T> $className
     *
     * @return EvaluationFlow
     */
    private function isDecisionWith(EvaluationFlow $evaluationFlow, string $className): EvaluationFlow
    {
        self::assertFalse($evaluationFlow->isEnd());
        self::assertInstanceOf($className, $evaluationFlow->getFlowEvaluator());
        return $evaluationFlow->getNextFlow();
    }
}
