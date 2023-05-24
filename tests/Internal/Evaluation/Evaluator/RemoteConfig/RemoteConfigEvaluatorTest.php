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

        $request = Models::remoteConfigRequest($parameter, "sdk_default");

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

    public function test__Evaluate_target_rule_match()
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

        $request = Models::remoteConfigRequest($parameter, "sdk_default");

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
}
