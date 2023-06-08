<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Condition\Experiment;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\Evaluator;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Match\Condition\Experiment\FeatureFlagConditionMatcher;
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

class FeatureFlagConditionMatcherTest extends TestCase
{

    private $evaluator;
    private $valueOperatorMatcher;
    private $sut;

    private $context;

    protected function setUp()
    {
        $this->evaluator = $this->createMock(Evaluator::class);
        $this->valueOperatorMatcher = $this->createMock(ValueOperatorMatcher::class);
        $this->sut = new FeatureFlagConditionMatcher($this->evaluator, $this->valueOperatorMatcher);

        $this->context = new EvaluatorContext();
    }

    public function test_when_key_is_not_numeric_then_throws_exception()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::FEATURE_FLAG()]);
        $request = Models::experimentRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::FEATURE_FLAG(), "string"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::BOOLEAN(), [true])
        );

        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid key [FEATURE_FLAG, string]");
        $this->sut->matches($request, $this->context, $condition);
    }

    public function test_when_experiment_not_found_then_returns_false()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::FEATURE_FLAG()]);
        $request = Models::experimentRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::FEATURE_FLAG(), "42"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::BOOLEAN(), [true])
        );

        // when
        $actual = $this->sut->matches($request, $this->context, $condition);

        // then
        self::assertFalse($actual);
    }

    public function test_when_request_has_already_been_evaluated_then_will_not_be_evaluated_again()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::FEATURE_FLAG()]);
        $request = $this->createRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::FEATURE_FLAG(), "42"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::BOOLEAN(), [true])
        );

        $evaluation = ExperimentEvaluation::of(
            $request,
            $this->context,
            $experiment->getVariations()[0],
            DecisionReason::DEFAULT_RULE()
        );
        $this->context->add($evaluation);
        $this->valueOperatorMatcher->method("matches")->willReturn(true);

        // when
        $actual = $this->sut->matches($request, $this->context, $condition);

        // then
        self::assertTrue($actual);
    }

    public function test_when_request_is_not_evaluated_then_evaluate_request()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::FEATURE_FLAG()]);
        $request = $this->createRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::FEATURE_FLAG(), "42"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::BOOLEAN(), [true])
        );

        $evaluation = ExperimentEvaluation::of(
            $request,
            $this->context,
            $experiment->getVariations()[0],
            DecisionReason::DEFAULT_RULE()
        );

        $this->evaluator->expects(self::once())->method("evaluate")->willReturn($evaluation);
        $this->valueOperatorMatcher->method("matches")->willReturn(true);

        // when
        $actual = $this->sut->matches($request, $this->context, $condition);

        // then
        self::assertTrue($actual);
    }

    public function createRequest(Experiment $experiment): ExperimentRequest
    {
        $workspace = $this->createMock(Workspace::class);
        $workspace->method("getFeatureFlagOrNull")->willReturn($experiment);
        return Models::experimentRequest($experiment, $workspace);
    }
}
