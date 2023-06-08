<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Condition\Experiment;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\Evaluator;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluation;
use Hackle\Internal\Evaluation\Match\Condition\Experiment\AbTestConditionMatcher;
use Hackle\Internal\Evaluation\Match\Value\ValueOperatorMatcher;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Internal\Model\MatchOperator;
use Hackle\Internal\Model\MatchType;
use Hackle\Internal\Model\TargetCondition;
use Hackle\Internal\Model\TargetKey;
use Hackle\Internal\Model\TargetKeyType;
use Hackle\Internal\Model\TargetMatch;
use Hackle\Internal\Model\ValueType;
use Hackle\Internal\Workspace\Workspace;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class AbTestConditionMatcherTest extends TestCase
{
    private $evaluator;
    private $valueOperatorMatcher;
    private $sut;

    private $context;

    protected function setUp()
    {
        parent::setUp();
        $this->evaluator = $this->createMock(Evaluator::class);
        $this->valueOperatorMatcher = $this->createMock(ValueOperatorMatcher::class);
        $this->sut = new AbTestConditionMatcher($this->evaluator, $this->valueOperatorMatcher);

        $this->context = new EvaluatorContext();
    }

    public function test_when_key_is_not_numeric_then_throws_exception()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
        $request = Models::experimentRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::AB_TEST(), "string"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["A"])
        );

        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid key [AB_TEST, string]");
        $this->sut->matches($request, $this->context, $condition);
    }

    public function test_when_experiment_not_found_then_returns_false()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
        $request = Models::experimentRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::AB_TEST(), "42"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["A"])
        );

        // when
        $actual = $this->sut->matches($request, $this->context, $condition);

        // then
        self::assertFalse($actual);
    }

    public function test_when_evaluation_is_not_reason_to_match_then_returns_false()
    {
        $check = function (DecisionReason $reason) {
            // given
            $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
            $request = $this->createRequest($experiment);
            $condition = new TargetCondition(
                new TargetKey(TargetKeyType::AB_TEST(), "42"),
                new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["A"])
            );
            $evaluation = ExperimentEvaluation::of(
                $request,
                $this->context,
                $experiment->getVariations()[0],
                $reason
            );

            $this->evaluator->method("evaluate")->willReturn($evaluation);

            // when
            $actual = $this->sut->matches($request, $this->context, $condition);

            // then
            self::assertFalse($actual);
        };

        $check(DecisionReason::EXPERIMENT_DRAFT());
        $check(DecisionReason::EXPERIMENT_PAUSED());
        $check(DecisionReason::NOT_IN_MUTUAL_EXCLUSION_EXPERIMENT());
        $check(DecisionReason::VARIATION_DROPPED());
        $check(DecisionReason::NOT_IN_EXPERIMENT_TARGET());
    }

    public function test_when_evaluation_is_reason_to_match_then_match_variation()
    {
        $check = function (DecisionReason $reason) {
            // given
            $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
            $request = $this->createRequest($experiment);
            $condition = new TargetCondition(
                new TargetKey(TargetKeyType::AB_TEST(), "42"),
                new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["A"])
            );
            $evaluation = ExperimentEvaluation::of(
                $request,
                $this->context,
                $experiment->getVariations()[0],
                $reason
            );

            $this->evaluator->method("evaluate")->willReturn($evaluation);
            $this->valueOperatorMatcher->method("matches")->willReturn(true);

            // when
            $actual = $this->sut->matches($request, $this->context, $condition);

            // then
            self::assertTrue($actual);
        };

        $check(DecisionReason::OVERRIDDEN());
        $check(DecisionReason::TRAFFIC_ALLOCATED());
        $check(DecisionReason::TRAFFIC_ALLOCATED_BY_TARGETING());
        $check(DecisionReason::EXPERIMENT_COMPLETED());
    }

    public function test_when_request_has_already_been_evaluated_then_will_not_be_evaluated_again()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
        $request = $this->createRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::AB_TEST(), "42"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["A"])
        );
        $evaluation = ExperimentEvaluation::of(
            $request,
            $this->context,
            $experiment->getVariations()[0],
            DecisionReason::TRAFFIC_ALLOCATED()
        );

        $this->context->add($evaluation);
        $this->valueOperatorMatcher->method("matches")->willReturn(true);

        // when
        $actual = $this->sut->matches($request, $this->context, $condition);

        // then
        self::assertTrue($actual);
    }

    public function test_when_evaluated_reason_is_TRAFFIC_ALLOCATED_then_replace_reason()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
        $request = $this->createRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::AB_TEST(), "42"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["A"])
        );
        $evaluation = ExperimentEvaluation::of(
            $request,
            $this->context,
            $experiment->getVariations()[0],
            DecisionReason::TRAFFIC_ALLOCATED()
        );

        $this->evaluator->expects(self::once())->method("evaluate")->willReturn($evaluation);
        $this->valueOperatorMatcher->method("matches")->willReturn(true);

        // when
        $actual = $this->sut->matches($request, $this->context, $condition);

        // then
        self::assertTrue($actual);
        self::assertEquals(
            DecisionReason::TRAFFIC_ALLOCATED_BY_TARGETING(),
            $this->context->get($experiment)->getReason()
        );
    }

    public function test_when_origin_request_is_not_experiment_request_then_use_evaluation_as_is()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
        $workspace = $this->createMock(Workspace::class);
        $workspace->method("getExperimentOrNull")->willReturn($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::AB_TEST(), "42"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["A"])
        );

        $experimentRequest = $this->createRequest($experiment);
        $evaluation = ExperimentEvaluation::of(
            $experimentRequest,
            $this->context,
            $experiment->getVariations()[0],
            DecisionReason::TRAFFIC_ALLOCATED()
        );

        $this->evaluator->expects(self::once())->method("evaluate")->willReturn($evaluation);
        $this->valueOperatorMatcher->method("matches")->willReturn(true);

        $request = Models::remoteConfigRequest(["workspace" => $workspace]);

        // when
        $actual = $this->sut->matches($request, $this->context, $condition);

        // then
        self::assertTrue($actual);
        self::assertSame($evaluation, $this->context->get($experiment));
    }

    public function test_when_evaluated_reason_is_not_TRAFFIC_ALLOCATED_then_use_evaluation_as_is()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
        $request = $this->createRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::AB_TEST(), "42"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["A"])
        );
        $evaluation = ExperimentEvaluation::of(
            $request,
            $this->context,
            $experiment->getVariations()[0],
            DecisionReason::OVERRIDDEN()
        );

        $this->evaluator->expects(self::once())->method("evaluate")->willReturn($evaluation);
        $this->valueOperatorMatcher->method("matches")->willReturn(true);

        // when
        $actual = $this->sut->matches($request, $this->context, $condition);

        // then
        self::assertTrue($actual);
        self::assertSame($evaluation, $this->context->get($experiment));
    }

    public function test_unexpected_evaluation()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
        $request = $this->createRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::AB_TEST(), "42"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["A"])
        );
        $evaluation = $this->createMock(RemoteConfigEvaluation::class);
        $this->evaluator->expects(self::once())->method("evaluate")->willReturn($evaluation);

        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unexpected evaluation");
        $this->sut->matches($request, $this->context, $condition);
    }

    public function createRequest(Experiment $experiment): ExperimentRequest
    {
        $workspace = $this->createMock(Workspace::class);
        $workspace->method("getExperimentOrNull")->willReturn($experiment);
        return Models::experimentRequest($experiment, $workspace);
    }
}
