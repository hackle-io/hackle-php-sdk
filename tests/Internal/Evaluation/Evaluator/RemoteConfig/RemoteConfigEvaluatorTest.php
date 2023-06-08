<?php

namespace Hackle\Tests\Internal\Evaluation\Evaluator\RemoteConfig;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluator;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigRequest;
use Hackle\Internal\Evaluation\Target\RemoteConfigTargetRuleDeterminer;
use Hackle\Internal\Model\RemoteConfigParameterValue;
use Hackle\Internal\Model\RemoteConfigTargetRule;
use Hackle\Internal\Model\Target;
use Hackle\Internal\Model\ValueType;
use Hackle\Tests\Internal\Model\Models;
use Mockery;
use PHPUnit\Framework\TestCase;

class RemoteConfigEvaluatorTest extends TestCase
{
    private $targetRuleDeterminer;
    private $sut;

    public function setUp()
    {
        $this->targetRuleDeterminer = Mockery::mock(RemoteConfigTargetRuleDeterminer::class);
        $this->sut = new RemoteConfigEvaluator($this->targetRuleDeterminer);
    }

    public function test__supports()
    {
        self::assertFalse($this->sut->supports(Mockery::mock(ExperimentRequest::class)));
        self::assertTrue($this->sut->supports(Mockery::mock(RemoteConfigRequest::class)));
    }

    public function test__evaluate__when_circular_called_then_throws_exception()
    {
        // given
        $request = Models::remoteConfigRequest();
        $context = new EvaluatorContext();
        $context->push($request);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Circular evaluation has occurred");

        // when
        $this->sut->evaluate($request, $context);
    }

    public function test__evaluate__when_identifier_is_null_then_default_value()
    {
        // given
        $parameter = Models::parameter([
            "type" => ValueType::STRING(),
            "identifierType" => "customId",
            "defaultValue" => new RemoteConfigParameterValue(42, "parameter_default")
        ]);

        $request = Models::remoteConfigRequest(["parameter" => $parameter, "defaultValue" => "sdk_default"]);

        // when
        $actual = $this->sut->evaluate($request, new EvaluatorContext());

        // then
        self::assertEquals(DecisionReason::IDENTIFIER_NOT_FOUND(), $actual->getReason());
        self::assertEquals("sdk_default", $actual->getValue());
        self::assertEquals([
            "requestValueType" => "STRING",
            "requestDefaultValue" => "sdk_default",
            "returnValue" => "sdk_default"
        ], $actual->getProperties());
    }

    public function test__evaluate_target_rule_match()
    {
        // given
        $targetRule = new RemoteConfigTargetRule(
            "target_rule_key",
            "target_rule_name",
            new Target([]),
            42,
            new RemoteConfigParameterValue(320, "target_rule_value")
        );

        $parameter = Models::parameter([
            "type" => ValueType::STRING(),
            "targetRules" => [$targetRule],
            "defaultValue" => new RemoteConfigParameterValue(43, "parameter_default")
        ]);

        $request = Models::remoteConfigRequest(["parameter" => $parameter, "defaultValue" => "sdk_default"]);

        $this->targetRuleDeterminer->allows(["determineTargetRuleOrNull" => $targetRule]);

        // when
        $actual = $this->sut->evaluate($request, new EvaluatorContext());

        // then
        self::assertEquals(DecisionReason::TARGET_RULE_MATCH, $actual->getReason());
        self::assertEquals(320, $actual->getValueId());
        self::assertEquals("target_rule_value", $actual->getValue());
        self::assertEquals([
            "requestValueType" => "STRING",
            "requestDefaultValue" => "sdk_default",
            "returnValue" => "target_rule_value",
            "targetRuleKey" => "target_rule_key",
            "targetRuleName" => "target_rule_name"
        ], $actual->getProperties());
    }

    public function test__evaluate__default_rule()
    {
        // given
        $targetRule = new RemoteConfigTargetRule(
            "target_rule_key",
            "target_rule_name",
            new Target([]),
            42,
            new RemoteConfigParameterValue(320, "target_rule_value")
        );

        $parameter = Models::parameter([
            "type" => ValueType::STRING(),
            "targetRules" => [$targetRule],
            "defaultValue" => new RemoteConfigParameterValue(43, "parameter_default")
        ]);

        $request = Models::remoteConfigRequest(["parameter" => $parameter, "defaultValue" => "sdk_default"]);

        $this->targetRuleDeterminer->allows(["determineTargetRuleOrNull" => null]);

        // when
        $actual = $this->sut->evaluate($request, new EvaluatorContext());

        // then
        self::assertEquals(DecisionReason::DEFAULT_RULE(), $actual->getReason());
        self::assertEquals(43, $actual->getValueId());
        self::assertEquals("parameter_default", $actual->getValue());
        self::assertEquals([
            "requestValueType" => "STRING",
            "requestDefaultValue" => "sdk_default",
            "returnValue" => "parameter_default",
        ], $actual->getProperties());
    }

    public function test__evaluate__type_match()
    {
        $this->assertTypeMatch(ValueType::STRING(), "match_string", "default_string", true);
        $this->assertTypeMatch(ValueType::STRING(), "", "default_string", true);
        $this->assertTypeMatch(ValueType::STRING(), 0, "default_string", false);
        $this->assertTypeMatch(ValueType::STRING(), 1, "default_string", false);
        $this->assertTypeMatch(ValueType::STRING(), false, "default_string", false);
        $this->assertTypeMatch(ValueType::STRING(), true, "default_string", false);

        $this->assertTypeMatch(ValueType::NUMBER(), 0, 999, true);
        $this->assertTypeMatch(ValueType::NUMBER(), 1, 999, true);
        $this->assertTypeMatch(ValueType::NUMBER(), -1, 999, true);
        $this->assertTypeMatch(ValueType::NUMBER(), 0.0, 999, true);
        $this->assertTypeMatch(ValueType::NUMBER(), 1.0, 999, true);
        $this->assertTypeMatch(ValueType::NUMBER(), -1.0, 999, true);
        $this->assertTypeMatch(ValueType::NUMBER(), 1.1, 999, true);
        $this->assertTypeMatch(ValueType::NUMBER(), "1", 999, false);
        $this->assertTypeMatch(ValueType::NUMBER(), "0", 999, false);
        $this->assertTypeMatch(ValueType::NUMBER(), true, 999, false);
        $this->assertTypeMatch(ValueType::NUMBER(), false, 999, false);

        $this->assertTypeMatch(ValueType::BOOLEAN(), true, false, true);
        $this->assertTypeMatch(ValueType::BOOLEAN(), false, true, true);
        $this->assertTypeMatch(ValueType::BOOLEAN(), 0, true, false);
        $this->assertTypeMatch(ValueType::BOOLEAN(), 1, false, false);

        $this->assertTypeMatch(ValueType::VERSION(), "1.0.0", "default", false);
        $this->assertTypeMatch(ValueType::JSON(), "{}", "default", false);
    }

    private function assertTypeMatch(ValueType $requiredType, $matchValue, $defaultValue, bool $isMatch)
    {
        $parameter = Models::parameter([
            "type" => ValueType::STRING(),
            "defaultValue" => new RemoteConfigParameterValue(43, $matchValue)
        ]);
        $request = Models::remoteConfigRequest([
            "parameter" => $parameter,
            "requiredType" => $requiredType,
            "defaultValue" => $defaultValue
        ]);

        $this->targetRuleDeterminer->allows(["determineTargetRuleOrNull" => null]);

        $actual = $this->sut->evaluate($request, new EvaluatorContext());

        if ($isMatch) {
            self::assertEquals(43, $actual->getValueId());
            self::assertEquals($matchValue, $actual->getValue());
            self::assertEquals(DecisionReason::DEFAULT_RULE(), $actual->getReason());
        } else {
            self::assertEquals(null, $actual->getValueId());
            self::assertEquals($defaultValue, $actual->getValue());
            self::assertEquals(DecisionReason::TYPE_MISMATCH(), $actual->getReason());
        }
    }
}
