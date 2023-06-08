<?php

namespace Hackle\Tests\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Flow\Evaluator\CompletedEvaluator;
use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Tests\Internal\Model\Models;

class CompletedEvaluatorTest extends FlowEvaluatorTest
{

    private $sut;

    public function setUp()
    {
        parent::setUp();
        $this->sut = new CompletedEvaluator();
    }


    public function test_when_completed_then_return_winner_variation()
    {
        // given
        $experiment = Models::experiment([
            "type" => ExperimentType::AB_TEST(),
            "status" => ExperimentStatus::COMPLETED(),
            "variations" => [
                Models::variation(42, "A"),
                Models::variation(43, "B")
            ],
            "winnerVariationId" => 43
        ]);

        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::EXPERIMENT_COMPLETED(), $actual->getReason());
        self::assertEquals(43, $actual->getVariationId());
    }

    public function test_when_completed_without_winner_variation_then_throws_exception()
    {
        // given
        $experiment = Models::experiment([
            "id" => 320,
            "type" => ExperimentType::AB_TEST(),
            "status" => ExperimentStatus::COMPLETED(),
            "variations" => [
                Models::variation(42, "A"),
                Models::variation(43, "B")
            ]
        ]);

        $request = Models::experimentRequest($experiment);

        // when
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Winner variation [320]");
        $this->sut->evaluate($request, $this->context, $this->nextFlow);
    }

    public function test_when_not_completed_then_evaluate_next_flow()
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
