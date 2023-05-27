<?php

namespace Hackle\Tests\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Action\ActionResolver;
use Hackle\Internal\Evaluation\Flow\Evaluator\TrafficAllocateEvaluator;
use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Tests\Internal\Model\Models;

class TrafficAllocateEvaluatorTest extends FlowEvaluatorTest
{
    private $actionResolver;
    private $sut;

    protected function setUp()
    {
        parent::setUp();
        $this->actionResolver = $this->createMock(ActionResolver::class);
        $this->sut = new TrafficAllocateEvaluator($this->actionResolver);
    }

    public function test_when_experiment_is_not_RUNNING_then_thorws_exceptioj()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::AB_TEST(),
            "status" => ExperimentStatus::DRAFT()
        ]);
        $request = Models::experimentRequest($experiment);

        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("experiment status must be RUNNING [42]");
        $this->sut->evaluate($request, $this->context, $this->nextFlow);
    }

    public function test_when_experiment_is_not_AB_TEST_then_throws_exception()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::FEATURE_FLAG(),
            "status" => ExperimentStatus::RUNNING()
        ]);
        $request = Models::experimentRequest($experiment);

        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("experiment type must be AB_TEST [42]");
        $this->sut->evaluate($request, $this->context, $this->nextFlow);
    }

    public function test_when_cannot_determine_variation_of_default_rule_then_returns_default_variation()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::AB_TEST(),
            "status" => ExperimentStatus::RUNNING()
        ]);
        $request = Models::experimentRequest($experiment);
        $this->actionResolver->method("resolveOrNull")->willReturn(null);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::TRAFFIC_NOT_ALLOCATED(), $actual->getReason());
        self::assertEquals("A", $actual->getVariationKey());
    }

    public function test_when_allocated_variation_is_dropped_then_return_default_variation()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::AB_TEST(),
            "status" => ExperimentStatus::RUNNING(),
            "variations" => [
                Models::variation(41, "A"),
                Models::variation(42, "B"),
                Models::variation(43, "C", true),
            ]
        ]);
        $variation = $experiment->getVariationOrNullByKey("C");
        $request = Models::experimentRequest($experiment);

        $this->actionResolver->method("resolveOrNull")->willReturn($variation);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::VARIATION_DROPPED(), $actual->getReason());
        self::assertEquals("A", $actual->getVariationKey());
    }

    public function test_when_variation_allocated_then_return_allocated_variation()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::AB_TEST(),
            "status" => ExperimentStatus::RUNNING(),
            "variations" => [
                Models::variation(41, "A"),
                Models::variation(42, "B"),
            ]
        ]);
        $variation = $experiment->getVariationOrNullByKey("B");
        $request = Models::experimentRequest($experiment);

        $this->actionResolver->method("resolveOrNull")->willReturn($variation);

        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::TRAFFIC_ALLOCATED(), $actual->getReason());
        self::assertEquals($variation->getId(), $actual->getVariationId());
    }
}
