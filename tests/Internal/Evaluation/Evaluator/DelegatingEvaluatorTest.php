<?php

namespace Hackle\Tests\Internal\Evaluation\Evaluator;

use Hackle\Internal\Evaluation\Evaluator\DelegatingEvaluator;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorEvaluation;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Mockery;
use PHPUnit\Framework\TestCase;

class DelegatingEvaluatorTest extends TestCase
{

    public function test__evaluate()
    {
        $sut = new DelegatingEvaluator();

        $r1 = Mockery::mock(EvaluatorRequest::class);
        $e1 = Mockery::mock(EvaluatorEvaluation::class);
        $evaluator1 = new MockEvaluator($r1, $e1);

        $sut->add($evaluator1);

        try {
            $sut->evaluate(Mockery::mock(EvaluatorRequest::class), new EvaluatorContext());
            self::fail();
        } catch (\InvalidArgumentException $e) {
        }

        self::assertEquals($e1, $sut->evaluate($r1, new EvaluatorContext()));

        $r2 = Mockery::mock(EvaluatorRequest::class);
        $e2 = Mockery::mock(EvaluatorEvaluation::class);
        $evaluator2 = new MockEvaluator($r2, $e2);

        $sut->add($evaluator2);
        self::assertEquals($e2, $sut->evaluate($r2, new EvaluatorContext()));
    }
}
