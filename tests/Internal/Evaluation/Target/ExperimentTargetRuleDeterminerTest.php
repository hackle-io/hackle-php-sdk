<?php

namespace Hackle\Tests\Internal\Evaluation\Target;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Match\TargetMatcher;
use Hackle\Internal\Evaluation\Target\ExperimentTargetRuleDeterminer;
use Hackle\Internal\Model\TargetRule;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class ExperimentTargetRuleDeterminerTest extends TestCase
{
    private $targetMatcher;
    private $sut;

    private $returns;
    private $callCount;

    protected function setUp()
    {
        $this->targetMatcher = $this->createMock(TargetMatcher::class);
        $this->sut = new ExperimentTargetRuleDeterminer($this->targetMatcher);

        $this->returns = [];
        $this->callCount = 0;
        $this->targetMatcher->method("matches")->willReturnCallback(function () {
            return $this->returns[$this->callCount++];
        });
    }

    public function test_when_target_rule_is_empty_then_returns_null()
    {
        // given
        $experiment = Models::experiment();
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->determineTargetRuleOrNull($request, new EvaluatorContext());

        // then
        self::assertNull($actual);
        self::assertEquals(0, $this->callCount);
    }

    public function test_returns_first_matching_target_rule()
    {
        // given
        $experiment = Models::experiment([
            "targetRules" => [
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(true),
                $this->createTargetRule(false)
            ]
        ]);
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->determineTargetRuleOrNull($request, new EvaluatorContext());

        // then
        self::assertSame($experiment->getTargetRules()[4], $actual);
        self::assertEquals(5, $this->callCount);
    }

    public function test_when_all_target_rule_is_not_match_then_returns_null()
    {
        // given
        $experiment = Models::experiment([
            "targetRules" => [
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(false)
            ]
        ]);
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->determineTargetRuleOrNull($request, new EvaluatorContext());

        // then
        self::assertNull($actual);
        self::assertEquals(6, $this->callCount);
    }

    private function createTargetRule(bool $isMatch): TargetRule
    {
        $this->returns[] = $isMatch;
        return $this->createMock(TargetRule::class);
    }
}
