<?php

namespace Hackle\Tests\Internal\Evaluation\Evaluator\RemoteConfig;

use Hackle\Common\DecisionReason;
use Hackle\Common\PropertiesBuilder;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorEvaluation;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluation;
use Hackle\Tests\Internal\Model\Models;
use Mockery;
use PHPUnit\Framework\TestCase;

class RemoteConfigEvaluationTest extends TestCase
{

    public function test_create()
    {
        $parameter = Models::parameter(["id" => 1]);

        $request = Models::remoteConfigRequest(["parameter" => $parameter]);
        $context = new EvaluatorContext();
        $context->add(Mockery::mock(EvaluatorEvaluation::class));

        $evaluation = RemoteConfigEvaluation::of(
            $request,
            $context,
            42,
            "go",
            DecisionReason::DEFAULT_RULE(),
            new PropertiesBuilder()
        );

        self::assertEquals(DecisionReason::DEFAULT_RULE(), $evaluation->getReason());
        self::assertEquals(1, count($evaluation->getTargetEvaluations()));
        self::assertEquals($parameter, $evaluation->getParameter());
        self::assertEquals(["returnValue" => "go"], $evaluation->getProperties());
    }

    public function test_create_by_default()
    {
        $parameter = Models::parameter(["id" => 1]);

        $request = Models::remoteConfigRequest(["parameter" => $parameter, "defaultValue" => 42]);
        $context = new EvaluatorContext();
        $context->add(Mockery::mock(EvaluatorEvaluation::class));

        $evaluation = RemoteConfigEvaluation::ofDefault(
            $request,
            $context,
            DecisionReason::DEFAULT_RULE(),
            new PropertiesBuilder()
        );

        self::assertEquals(DecisionReason::DEFAULT_RULE(), $evaluation->getReason());
        self::assertEquals(1, count($evaluation->getTargetEvaluations()));
        self::assertEquals($parameter, $evaluation->getParameter());
        self::assertEquals(["returnValue" => "42"], $evaluation->getProperties());
    }
}
