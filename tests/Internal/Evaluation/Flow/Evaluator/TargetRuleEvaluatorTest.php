<?php

namespace Hackle\Tests\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Action\ActionResolver;
use Hackle\Internal\Evaluation\Flow\Evaluator\TargetRuleEvaluator;
use Hackle\Internal\Evaluation\Target\ExperimentTargetRuleDeterminer;
use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Internal\Model\TargetRule;
use Hackle\Tests\Internal\Model\Models;

class TargetRuleEvaluatorTest extends FlowEvaluatorTest
{
    private $targetRuleDeterminer;
    private $actionResolver;
    private $sut;

    protected function setUp()
    {
        parent::setUp();
        $this->targetRuleDeterminer = $this->createMock(ExperimentTargetRuleDeterminer::class);
        $this->actionResolver = $this->createMock(ActionResolver::class);
        $this->sut = new TargetRuleEvaluator($this->targetRuleDeterminer, $this->actionResolver);
    }

    public function test_when_experiment_is_not_running_then_thorws_exception()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::FEATURE_FLAG(),
            "status" => ExperimentStatus::DRAFT()
        ]);
        $request = Models::experimentRequest($experiment);

        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("experiment status must be RUNNING [42]");
        $this->sut->evaluate($request, $this->context, $this->nextFlow);
    }

    public function test_when_experiment_is_not_feature_flag_then_thorws_exception()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::AB_TEST(),
            "status" => ExperimentStatus::RUNNING()
        ]);
        $request = Models::experimentRequest($experiment);

        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("experiment type must be FEATURE_FLAG [42]");
        $this->sut->evaluate($request, $this->context, $this->nextFlow);
    }


    public function test_when_identifier_not_found_then_evaluate_next_flow()
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
        self::assertSame($this->evaluation, $actual);
    }

    public function test_when_does_not_match_target_rule_then_evaluate_next_flow()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::FEATURE_FLAG(),
            "status" => ExperimentStatus::RUNNING()
        ]);
        $request = Models::experimentRequest($experiment);

        $this->targetRuleDeterminer->method("determineTargetRuleOrNull")->willReturn(null);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals($this->evaluation, $actual);
    }

    public function test_when_cannot_determine_variation_of_target_rule_then_throws_exception()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::FEATURE_FLAG(),
            "status" => ExperimentStatus::RUNNING()
        ]);
        $request = Models::experimentRequest($experiment);
        $targetRule = $this->createMock(TargetRule::class);

        $this->targetRuleDeterminer->method("determineTargetRuleOrNull")->willReturn($targetRule);
        $this->actionResolver->method("resolveOrNull")->willReturn(null);


        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("FeatureFlag must decide the variation [42]");
        $this->sut->evaluate($request, $this->context, $this->nextFlow);
    }

    public function test_when_matched_by_target_rule_then_returns_matched_variation()
    {
        // given
        $experiment = Models::experiment([
            "id" => 42,
            "type" => ExperimentType::FEATURE_FLAG(),
            "status" => ExperimentStatus::RUNNING()
        ]);
        $request = Models::experimentRequest($experiment);
        $targetRule = $this->createMock(TargetRule::class);

        $variation = $experiment->getVariations()[1];

        $this->targetRuleDeterminer->method("determineTargetRuleOrNull")->willReturn($targetRule);
        $this->actionResolver->method("resolveOrNull")->willReturn($variation);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::TARGET_RULE_MATCH(), $actual->getReason());
        self::assertEquals($variation->getId(), $actual->getVariationId());
    }
}
