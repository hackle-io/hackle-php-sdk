<?php

namespace Hackle\Tests\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Flow\Evaluator\ExperimentTargetEvaluator;
use Hackle\Internal\Evaluation\Target\ExperimentTargetDeterminer;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Tests\Internal\Model\Models;

class ExperimentTargetEvaluatorTest extends FlowEvaluatorTest
{
    private $experimentTargetDeterminer;
    private $sut;

    protected function setUp()
    {
        parent::setUp();
        $this->experimentTargetDeterminer = $this->createMock(ExperimentTargetDeterminer::class);
        $this->sut = new ExperimentTargetEvaluator($this->experimentTargetDeterminer);
    }

    public function test_when_experiment_is_not_AB_TEST_type_then_throws_exception()
    {
        // given
        $experiment = Models::experiment(["id" => 42, "type" => ExperimentType::FEATURE_FLAG()]);
        $request = Models::experimentRequest($experiment);

        // when
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("experiment type must be AB_TEST [42]");
        $this->sut->evaluate($request, $this->context, $this->nextFlow);
    }


    public function test_when_user_is_experiment_target_then_evaluate_next_flow()
    {
        // given
        $experiment = Models::experiment(["id" => 42, "type" => ExperimentType::AB_TEST()]);
        $request = Models::experimentRequest($experiment);

        $this->experimentTargetDeterminer->method("isUserInExperimentTarget")->willReturn(true);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertSame($this->evaluation, $actual);
    }

    public function test_when_user_is_not_in_experiment_target_then_returns_default_variation()
    {
        // given
        $experiment = Models::experiment(["id" => 42, "type" => ExperimentType::AB_TEST()]);
        $request = Models::experimentRequest($experiment);

        $this->experimentTargetDeterminer->method("isUserInExperimentTarget")->willReturn(false);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::NOT_IN_EXPERIMENT_TARGET(), $actual->getReason());
        self::assertEquals("A", $actual->getVariationKey());
    }
}
