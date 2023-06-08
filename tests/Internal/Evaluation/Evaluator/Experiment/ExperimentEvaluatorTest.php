<?php

namespace Hackle\Tests\Internal\Evaluation\Evaluator\Experiment;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluator;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use Hackle\Internal\Evaluation\Flow\EvaluationFlowFactory;
use Hackle\Tests\Internal\Model\Models;
use Mockery;
use PHPUnit\Framework\TestCase;

class ExperimentEvaluatorTest extends TestCase
{
    private $evaluationFlowFactory;
    private $sut;

    public function setUp()
    {
        $this->evaluationFlowFactory = Mockery::mock(EvaluationFlowFactory::class);
        $this->sut = new ExperimentEvaluator($this->evaluationFlowFactory);
    }

    public function test__supports()
    {
        self::assertTrue($this->sut->supports(Mockery::mock(ExperimentRequest::class)));
        self::assertFalse($this->sut->supports(Mockery::mock(RemoteConfigRequest::class)));
    }

    public function test__evaluate__when_circular_called_then_throws_exception()
    {
        $request = Models::experimentRequest();
        $context = new EvaluatorContext();
        $context->push($request);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Circular evaluation has occurred");

        $this->sut->evaluate($request, $context);
    }

    public function test__evaluate__flow()
    {
        // given
        $evaluation = Mockery::mock(ExperimentEvaluation::class);
        $evaluationFlow = Mockery::mock(EvaluationFlow::class);
        $evaluationFlow->allows(["evaluate" => $evaluation]);

        $this->evaluationFlowFactory->allows(["getFlow" => $evaluationFlow]);

        $request = Models::experimentRequest();
        $context = new EvaluatorContext();

        // when
        $actual = $this->sut->evaluate($request, $context);

        // then
        self::assertEquals($evaluation, $actual);
    }
}
