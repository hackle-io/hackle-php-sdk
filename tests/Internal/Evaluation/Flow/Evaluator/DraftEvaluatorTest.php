<?php

namespace Hackle\Tests\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Flow\Evaluator\DraftEvaluator;
use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Tests\Internal\Model\Models;

class DraftEvaluatorTest extends FlowEvaluatorTest
{

    private $sut;

    protected function setUp()
    {
        parent::setUp();
        $this->sut = new DraftEvaluator();
    }

    public function test_when_experiment_is_DRAFT_status_then_return_default_variation()
    {
        // given
        $experiment = Models::experiment(["status" => ExperimentStatus::DRAFT()]);
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::EXPERIMENT_DRAFT(), $actual->getReason());
        self::assertEquals("A", $actual->getVariationKey());
    }

    public function test_when_experiment_is_NOT_DRAFT_then_evaluate_next_flow()
    {
        // given
        $experiment = Models::experiment(["status" => ExperimentStatus::RUNNING()]);
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertSame($this->evaluation, $actual);
    }
}
