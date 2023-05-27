<?php

namespace Hackle\Tests\Internal\Evaluation\Flow\Evaluator;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Flow\EvaluationFlow;
use PHPUnit\Framework\TestCase;

abstract class FlowEvaluatorTest extends TestCase
{
    protected $evaluation;
    protected $nextFlow;
    protected $context;

    protected function setUp()
    {
        $this->evaluation = $this->createMock(ExperimentEvaluation::class);
        $this->nextFlow = $this->createMock(EvaluationFlow::class);
        $this->nextFlow->method("evaluate")->willReturn($this->evaluation);
        $this->context = new EvaluatorContext();
    }
}
