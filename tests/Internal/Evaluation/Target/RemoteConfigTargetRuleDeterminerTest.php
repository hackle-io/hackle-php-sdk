<?php

namespace Hackle\Tests\Internal\Evaluation\Target;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Target\RemoteConfigTargetMatcher;
use Hackle\Internal\Evaluation\Target\RemoteConfigTargetRuleDeterminer;
use Hackle\Internal\Model\RemoteConfigTargetRule;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class RemoteConfigTargetRuleDeterminerTest extends TestCase
{

    private $remoteConfigMatcher;
    private $sut;

    private $returns;
    private $callCount;

    protected function setUp()
    {
        $this->remoteConfigMatcher = $this->createMock(RemoteConfigTargetMatcher::class);
        $this->sut = new RemoteConfigTargetRuleDeterminer($this->remoteConfigMatcher);

        $this->returns = [];
        $this->callCount = 0;
        $this->remoteConfigMatcher->method("matches")->willReturnCallback(function () {
            return $this->returns[$this->callCount++];
        });
    }

    public function test_when_target_rule_is_empty_then_returns_null()
    {
        // given
        $parameter = Models::parameter();
        $request = Models::remoteConfigRequest(["parameter" => $parameter]);

        // when
        $actual = $this->sut->determineTargetRuleOrNull($request, new EvaluatorContext());

        // then
        self::assertNull($actual);
    }

    public function test_returns_first_matching_target_rule()
    {
        // given
        $parameter = Models::parameter([
            "targetRules" => [
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(true),
                $this->createTargetRule(false)
            ]
        ]);
        $request = Models::remoteConfigRequest(["parameter" => $parameter]);

        // when
        $actual = $this->sut->determineTargetRuleOrNull($request, new EvaluatorContext());

        // then
        self::assertNotNull($actual);
        self::assertSame($parameter->getTargetRules()[3], $actual);
        self::assertEquals(4, $this->callCount);
    }

    public function test_when_all_target_rules_do_not_match_then_returns_null()
    {
        // given
        $parameter = Models::parameter([
            "targetRules" => [
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(false),
                $this->createTargetRule(false)
            ]
        ]);
        $request = Models::remoteConfigRequest(["parameter" => $parameter]);

        // when
        $actual = $this->sut->determineTargetRuleOrNull($request, new EvaluatorContext());

        // then
        self::assertNull($actual);
        self::assertEquals(5, $this->callCount);
    }

    private function createTargetRule(bool $isMatch): RemoteConfigTargetRule
    {
        $this->returns[] = $isMatch;
        return $this->createMock(RemoteConfigTargetRule::class);
    }
}
