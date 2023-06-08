<?php

namespace Hackle\Tests\Internal\Evaluation\Flow;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use Hackle\Internal\Evaluation\Flow\Evaluator\FlowEvaluator;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class EvaluationFlowTest extends TestCase
{

    public function test__evaluate__end()
    {
        $request = Models::experimentRequest();
        $evaluationFlow = new EvaluationFlow(null, null);
        $evaluation = $evaluationFlow->evaluate($request, new EvaluatorContext());
        self::assertEquals(DecisionReason::TRAFFIC_NOT_ALLOCATED(), $evaluation->getReason());
        self::assertEquals("A", $evaluation->getVariationKey());
    }

    public function test__evaluate__flow()
    {
        $request = Models::experimentRequest();
        $context = new EvaluatorContext();

        $evaluation = $this->createMock(ExperimentEvaluation::class);
        $nextFlow = $this->createMock(EvaluationFlow::class);
        $flowEvaluator = $this->createMock(FlowEvaluator::class);
        $flowEvaluator->expects(self::once())->method("evaluate")->willReturn($evaluation);

        $sut = new EvaluationFlow($flowEvaluator, $nextFlow);

        $actual = $sut->evaluate($request, $context);

        self::assertSame($evaluation, $actual);
    }

    public function test__of()
    {
        $fe1 = $this->createMock(FlowEvaluator::class);
        $fe2 = $this->createMock(FlowEvaluator::class);
        $fe3 = $this->createMock(FlowEvaluator::class);

        $flow = EvaluationFlow::of($fe1, $fe2, $fe3);
        self::assertFalse($flow->isEnd());
        self::assertSame($fe1, $flow->getFlowEvaluator());

        $flow = $flow->getNextFlow();
        self::assertFalse($flow->isEnd());
        self::assertSame($fe2, $flow->getFlowEvaluator());

        $flow = $flow->getNextFlow();
        self::assertFalse($flow->isEnd());
        self::assertSame($fe3, $flow->getFlowEvaluator());

        $flow = $flow->getNextFlow();
        self::assertTrue($flow->isEnd());
    }
}
