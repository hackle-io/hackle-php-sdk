<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Condition\Experiment;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Match\Condition\Experiment\AbTestConditionMatcher;
use Hackle\Internal\Evaluation\Match\Condition\Experiment\ExperimentConditionMatcher;
use Hackle\Internal\Evaluation\Match\Condition\Experiment\FeatureFlagConditionMatcher;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Internal\Model\MatchOperator;
use Hackle\Internal\Model\MatchType;
use Hackle\Internal\Model\TargetCondition;
use Hackle\Internal\Model\TargetKey;
use Hackle\Internal\Model\TargetKeyType;
use Hackle\Internal\Model\TargetMatch;
use Hackle\Internal\Model\ValueType;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class ExperimentConditionMatcherTest extends TestCase
{
    private $abTestMatcher;
    private $featureFlagMatcher;
    private $sut;

    protected function setUp()
    {
        $this->abTestMatcher = $this->createMock(AbTestConditionMatcher::class);
        $this->featureFlagMatcher = $this->createMock(FeatureFlagConditionMatcher::class);
        $this->sut = new ExperimentConditionMatcher($this->abTestMatcher, $this->featureFlagMatcher);
    }


    public function test_AB_TEST()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
        $request = Models::experimentRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::AB_TEST(), "42"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["A"])
        );

        $this->abTestMatcher->method("matches")->willReturn(true);

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $condition);

        // then
        self::assertTrue($actual);
    }

    public function test_FEATURE_FLAG()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::FEATURE_FLAG()]);
        $request = Models::experimentRequest($experiment);
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::FEATURE_FLAG(), "42"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::BOOLEAN(), [true])
        );

        $this->featureFlagMatcher->method("matches")->willReturn(true);

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $condition);

        // then
        self::assertTrue($actual);
    }

    public function test_unsupported()
    {
        $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
        $request = Models::experimentRequest($experiment);

        $verify = function (TargetKeyType $keyType) use ($request) {
            $condition = new TargetCondition(
                new TargetKey($keyType, "42"),
                new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["A"])
            );

            try {
                $this->sut->matches($request, new EvaluatorContext(), $condition);
                self::fail();
            } catch (\InvalidArgumentException $e) {
                self::assertStringContainsString("Unsupported TargetKeyType", $e->getMessage());
            }
        };

        $verify(TargetKeyType::USER_ID());
        $verify(TargetKeyType::USER_PROPERTY());
        $verify(TargetKeyType::HACKLE_PROPERTY());
        $verify(TargetKeyType::SEGMENT());
    }
}
