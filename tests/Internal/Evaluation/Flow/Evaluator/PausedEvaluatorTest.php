<?php

namespace Hackle\Tests\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Flow\Evaluator\PausedEvaluator;
use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Tests\Internal\Model\Models;

class PausedEvaluatorTest extends FlowEvaluatorTest
{
    private $sut;

    protected function setUp()
    {
        parent::setUp();
        $this->sut = new PausedEvaluator();
    }

    public function test_AB_TEST_paused()
    {
        // given
        $experiment = Models::experiment([
            "type" => ExperimentType::AB_TEST(),
            "status" => ExperimentStatus::PAUSED()
        ]);
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::EXPERIMENT_PAUSED(), $actual->getReason());
        self::assertEquals("A", $actual->getVariationKey());
    }

    public function test_FEATURE_FLAG_paused()
    {
        // given
        $experiment = Models::experiment([
            "type" => ExperimentType::FEATURE_FLAG(),
            "status" => ExperimentStatus::PAUSED()
        ]);
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::FEATURE_FLAG_INACTIVE(), $actual->getReason());
        self::assertEquals("A", $actual->getVariationKey());
    }

    public function test_when_experiment_is_not_paused_then_evaluate_next_flow()
    {
        // given
        $experiment = Models::experiment([
            "type" => ExperimentType::AB_TEST(),
            "status" => ExperimentStatus::RUNNING()
        ]);
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertSame($this->evaluation, $actual);
    }
}
