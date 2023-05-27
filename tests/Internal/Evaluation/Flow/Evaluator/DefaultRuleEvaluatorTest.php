<?php

namespace Hackle\Tests\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Action\ActionResolver;
use Hackle\Internal\Evaluation\Flow\Evaluator\DefaultRuleEvaluator;
use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Tests\Internal\Model\Models;

class DefaultRuleEvaluatorTest extends FlowEvaluatorTest
{

    private $actionResolver;
    private $sut;

    public function setUp()
    {
        parent::setUp();
        $this->actionResolver = $this->createMock(ActionResolver::class);
        $this->sut = new DefaultRuleEvaluator($this->actionResolver);
    }

    public function test_when_not_running_then_throws_exception()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::FEATURE_FLAG(),
            "status" => ExperimentStatus::DRAFT()
        ]);

        $request = Models::experimentRequest($experiment);

        // when
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("experiment status must be RUNNING [42]");
        $this->sut->evaluate($request, $this->context, $this->nextFlow);
    }

    public function test_when_experiment_is_not_FEATURE_FLAG_then_throws_exception()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::AB_TEST(),
            "status" => ExperimentStatus::RUNNING()
        ]);

        $request = Models::experimentRequest($experiment);

        // when
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("experiment type must be FEATURE_FLAG [42]");
        $this->sut->evaluate($request, $this->context, $this->nextFlow);
    }

    public function test_when_identifier_not_found_then_returns_default_variation()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::FEATURE_FLAG(),
            "status" => ExperimentStatus::RUNNING(),
            "identifierType" => "customId"
        ]);

        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::DEFAULT_RULE(), $actual->getReason());
        self::assertEquals("A", $actual->getVariationKey());
    }

    public function test_when_cannot_determine_variation_of_default_rule_then_throws_exception()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::FEATURE_FLAG(),
            "status" => ExperimentStatus::RUNNING(),
        ]);

        $request = Models::experimentRequest($experiment);
        $this->actionResolver->method("resolveOrNull")->willReturn(null);

        // when
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("FeatureFlag must decide the variation [42]");
        $this->sut->evaluate($request, $this->context, $this->nextFlow);
    }

    public function test_default_rule()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::FEATURE_FLAG(),
            "status" => ExperimentStatus::RUNNING(),
        ]);
        $variation = $experiment->getVariations()[1];

        $request = Models::experimentRequest($experiment);
        $this->actionResolver->method("resolveOrNull")->willReturn($variation);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::DEFAULT_RULE(), $actual->getReason());
        self::assertEquals($variation->getId(), $actual->getVariationId());
    }
}
