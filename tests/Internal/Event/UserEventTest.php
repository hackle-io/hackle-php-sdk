<?php

namespace Hackle\Tests\Internal\Event;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Event\ExposureEvent;
use Hackle\Internal\Event\UserEvent;
use Hackle\Internal\Model\ParameterConfiguration;
use Hackle\Internal\User\HackleUser;
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
}
