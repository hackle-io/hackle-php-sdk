<?php

namespace Hackle\Tests\Internal\Event;

use Hackle\Common\DecisionReason;
use Hackle\Common\Event;
use Hackle\Common\User;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluation;
use Hackle\Internal\Event\ExposureEvent;
use Hackle\Internal\Event\RemoteConfigEvent;
use Hackle\Internal\Event\UserEvent;
use Hackle\Internal\Model\EventType;
use Hackle\Internal\Model\ParameterConfiguration;
use Hackle\Internal\Model\RemoteConfigParameter;
use Hackle\Internal\User\HackleUser;
use Hackle\Internal\User\HackleUserResolver;
use Hackle\Internal\User\IdentifierType;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class UserEventTest extends TestCase
{
    public function testExposureCreate()
    {
        $parameterConfiguration = new ParameterConfiguration(42, array());
        $evaluation = new ExperimentEvaluation(
            DecisionReason::TRAFFIC_ALLOCATED(),
            array(),
            Models::getExperiment(),
            42,
            "B",
            $parameterConfiguration
        );
        $user = HackleUser::builder()->identifier(IdentifierType::ID(), "test_id")->build();
        $actual = UserEvent::exposure($user, $evaluation, array("a" => "1"), 320);
        self::assertInstanceOf(ExposureEvent::class, $actual);
        self::assertEquals(1, $actual->getProperties()["a"]);
        self::assertEquals(320, $actual->getTimestamp());
        self::assertEquals(DecisionReason::TRAFFIC_ALLOCATED(), $actual->getDecisionReason());
    }

    public function testRemoteConfigCreate()
    {
        $remoteConfigParameter = $this->createMock(RemoteConfigParameter::class);
        $user = (new HackleUserResolver())->resolveOrNull(User::of("id"));
        $evaluation = new RemoteConfigEvaluation(
            DecisionReason::DEFAULT_RULE(),
            array(),
            $remoteConfigParameter,
            42,
            "remote config value",
            array("a" => "1")
        );

        $remoteConfigEvent = UserEvent::remoteConfig($user, $evaluation, array("b" => "2"), 320);
        self::assertInstanceOf(RemoteConfigEvent::class, $remoteConfigEvent);
        self::assertSame($remoteConfigParameter, $remoteConfigEvent->getParameter());
        self::assertEquals(42, $remoteConfigEvent->getValueId());
        self::assertEquals(DecisionReason::DEFAULT_RULE(), $remoteConfigEvent->getDecisionReason());
        self::assertEquals("2", $remoteConfigEvent->getProperties()["b"]);
    }

    public function testTrack()
    {
        $user = HackleUser::builder()->build();
        $eventType = new EventType(320, "event");
        $event = Event::of("event");

        $trackEvent = UserEvent::track(
            $user,
            $eventType,
            $event,
            42
        );

        self::assertEquals(42, $trackEvent->getTimestamp());
        self::assertSame($user, $trackEvent->getUser());
        self::assertSame($eventType, $trackEvent->getEventType());
        self::assertSame($event, $trackEvent->getEvent());
    }
}
