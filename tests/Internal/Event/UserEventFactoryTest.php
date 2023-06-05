<?php

namespace Hackle\Tests\Internal\Event;

use Hackle\Common\DecisionReason;
use Hackle\Common\PropertiesBuilder;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluation;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigRequest;
use Hackle\Internal\Event\ExposureEvent;
use Hackle\Internal\Event\RemoteConfigEvent;
use Hackle\Internal\Event\UserEventFactory;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Internal\Model\ParameterConfiguration;
use Hackle\Internal\Model\RemoteConfigParameter;
use Hackle\Internal\Time\SystemClock;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class UserEventFactoryTest extends TestCase
{
    public function testCreate()
    {

        $clock = $this->createMock(SystemClock::class);
        $clock->method("currentMillis")->willReturn(47);
        $clock->method("tick")->willReturn(48);

        $sut = new UserEventFactory($clock);
        $context = new EvaluatorContext();

        $evaluation1 = new ExperimentEvaluation(
            DecisionReason::TRAFFIC_ALLOCATED(),
            array(),
            Models::experiment(),
            42,
            "B",
            new ParameterConfiguration(42, array())
        );

        $evaluation2 = new ExperimentEvaluation(
            DecisionReason::DEFAULT_RULE(),
            array(),
            Models::experiment(["id" => 2, "type" => ExperimentType::FEATURE_FLAG()]),
            320,
            "A",
            null
        );

        $context->add($evaluation1);
        $context->add($evaluation2);

        $request = Models::remoteConfigRequest();
        $evaluation = RemoteConfigEvaluation::of(
            $request,
            $context,
            999,
            "RC",
            DecisionReason::TARGET_RULE_MATCH(),
            new PropertiesBuilder()
        );

        $events = $sut->create($request, $evaluation);
        self::assertCount(3, $events);

        $actual = $events[0];
        self::assertInstanceOf(RemoteConfigEvent::class, $actual);
        self::assertEquals(47, $actual->getTimestamp());
        self::assertSame($request->getUser(), $actual->getUser());
        self::assertSame($request->getParameter(), $actual->getParameter());
        self::assertEquals(999, $actual->getValueId());
        self::assertEquals(DecisionReason::TARGET_RULE_MATCH(), $actual->getDecisionReason());
        self::assertEquals(array("returnValue" => "RC"), $actual->getProperties());

        $actual = $events[1];
        self::assertInstanceOf(ExposureEvent::class, $actual);
        self::assertEquals(47, $actual->getTimestamp());
        self::assertSame($request->getUser(), $actual->getUser());
        self::assertSame($evaluation1->getExperiment(), $actual->getExperiment());
        self::assertEquals(42, $actual->getVariationId());
        self::assertEquals("B", $actual->getVariationKey());
        self::assertEquals(DecisionReason::TRAFFIC_ALLOCATED(), $actual->getDecisionReason());
        self::assertEquals(
            array(
                "\$targetingRootType" => "REMOTE_CONFIG",
                "\$targetingRootId" => 1,
                "\$parameterConfigurationId" => 42,
            ),
            $actual->getProperties()
        );

        $actual = $events[2];
        self::assertInstanceOf(ExposureEvent::class, $actual);
        self::assertEquals(47, $actual->getTimestamp());
        self::assertSame($request->getUser(), $actual->getUser());
        self::assertSame($evaluation2->getExperiment(), $actual->getExperiment());
        self::assertEquals(320, $actual->getVariationId());
        self::assertEquals("A", $actual->getVariationKey());
        self::assertEquals(DecisionReason::DEFAULT_RULE(), $actual->getDecisionReason());
        self::assertEquals(
            array(
                "\$targetingRootType" => "REMOTE_CONFIG",
                "\$targetingRootId" => 1
            ),
            $actual->getProperties()
        );
    }
}
