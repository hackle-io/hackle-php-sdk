<?php

namespace Hackle\Tests\Internal\Core;

use Hackle\Common\DecisionReason;
use Hackle\Common\ExperimentDecision;
use Hackle\Common\FeatureFlagDecision;
use Hackle\Common\HackleEvent;
use Hackle\Common\RemoteConfigDecision;
use Hackle\Internal\Core\HackleCore;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluator;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluation;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluator;
use Hackle\Internal\Event\ExposureEvent;
use Hackle\Internal\Event\RemoteConfigEvent;
use Hackle\Internal\Event\TrackEvent;
use Hackle\Internal\Event\UserEvent;
use Hackle\Internal\Event\UserEventFactory;
use Hackle\Internal\Model\EventType;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Internal\Model\ParameterConfiguration;
use Hackle\Internal\Model\ValueType;
use Hackle\Internal\User\IdentifierType;
use Hackle\Internal\User\InternalHackleUser;
use Hackle\Internal\Workspace\WorkspaceFetcher;
use Hackle\Tests\Internal\Model\Models;
use Hackle\Tests\Internal\Time\FixedClock;
use Hackle\Tests\Internal\Workspace\ResourcesWorkspaceFetcher;
use PHPUnit\Framework\TestCase;

class HackleCoreTest extends TestCase
{
    private $experimentEvaluator;
    private $remoteConfigEvaluator;
    private $workspaceFetcher;
    private $eventFactory;
    private $eventProcessor;
    private $clock;
    private $sut;

    private $user;

    protected function setUp()
    {
        $this->experimentEvaluator = $this->createMock(ExperimentEvaluator::class);
        $this->remoteConfigEvaluator = $this->createMock(RemoteConfigEvaluator::class);
        $this->workspaceFetcher = $this->createMock(WorkspaceFetcher::class);
        $this->eventFactory = $this->createMock(UserEventFactory::class);
        $this->eventProcessor = new InMemoryUserEventProcessor();
        $this->clock = new FixedClock(42, 320);
        $this->sut = new HackleCore(
            $this->experimentEvaluator,
            $this->remoteConfigEvaluator,
            $this->workspaceFetcher,
            $this->eventFactory,
            $this->eventProcessor,
            $this->clock
        );

        $this->user = InternalHackleUser::builder()->identifier(IdentifierType::ID(), "user")->build();
    }


    public function test__experiment__when_workspace_is_null_then_returns_default_variation_and_do_not_send_events()
    {
        // given
        $this->workspaceFetcher->method("fetch")->willReturn(null);

        // when
        $actual = $this->sut->experiment(42, $this->user, "A");

        // then
        self::assertEquals(ExperimentDecision::of("A", DecisionReason::SDK_NOT_READY()), $actual);
        self::assertEquals(0, count($this->eventProcessor->getEvents()));
    }

    public function test__experiment__when_experiment_not_found_then_returns_default_variation_and_do_not_send_events()
    {
        // given
        $workspace = Models::workspace();
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        // when
        $actual = $this->sut->experiment(42, $this->user, "A");

        // then
        self::assertEquals(ExperimentDecision::of("A", DecisionReason::EXPERIMENT_NOT_FOUND()), $actual);
        self::assertEquals(0, count($this->eventProcessor->getEvents()));
    }


    public function test__experiment__evaluate_and_process_events()
    {
        // given
        $experiment = Models::experiment(["key" => 42]);
        $workspace = Models::workspace(["experiments" => [$experiment]]);
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        $config = new ParameterConfiguration(320, []);
        $evaluation = new ExperimentEvaluation(
            DecisionReason::TRAFFIC_ALLOCATED(),
            [],
            $experiment,
            2,
            "B",
            $config
        );
        $this->experimentEvaluator->method("evaluate")->willReturn($evaluation);
        $this->eventFactory->method("create")->willReturn([
            $this->createMock(UserEvent::class),
            $this->createMock(UserEvent::class)
        ]);

        // when
        $actual = $this->sut->experiment(42, $this->user, "A");

        // then
        self::assertEquals(ExperimentDecision::of("B", DecisionReason::TRAFFIC_ALLOCATED(), $config), $actual);
        self::assertEquals(2, count($this->eventProcessor->getEvents()));
    }

    public function test__feature_flag__when_workspace_is_null_then_returns_false_and_do_not_send_events()
    {
        // given
        $this->workspaceFetcher->method("fetch")->willReturn(null);

        // when
        $actual = $this->sut->featureFlag(42, $this->user);

        // then
        self::assertEquals(FeatureFlagDecision::off(DecisionReason::SDK_NOT_READY()), $actual);
        self::assertEquals(0, count($this->eventProcessor->getEvents()));
    }

    public function test__feature_flag__when_feature_flag_not_found_then_returns_false_and_do_not_send_events()
    {
        // given
        $workspace = Models::workspace();
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        // when
        $actual = $this->sut->featureFlag(42, $this->user);

        // then
        self::assertEquals(FeatureFlagDecision::off(DecisionReason::FEATURE_FLAG_NOT_FOUND()), $actual);
        self::assertEquals(0, count($this->eventProcessor->getEvents()));
    }

    public function test__feature_flag__when_evaluated_as_A_then_returns_false()
    {
        // given
        $experiment = Models::experiment(["key" => 42, "type" => ExperimentType::FEATURE_FLAG()]);
        $workspace = Models::workspace(["featureFlags" => [$experiment]]);
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);
        $config = new ParameterConfiguration(320, []);
        $evaluation = new ExperimentEvaluation(
            DecisionReason::TARGET_RULE_MATCH(),
            [],
            $experiment,
            1,
            "A",
            $config
        );
        $this->experimentEvaluator->method("evaluate")->willReturn($evaluation);
        $this->eventFactory->method("create")->willReturn([
            $this->createMock(UserEvent::class),
            $this->createMock(UserEvent::class)
        ]);

        // when
        $actual = $this->sut->featureFlag(42, $this->user);

        // then
        self::assertEquals(FeatureFlagDecision::off(DecisionReason::TARGET_RULE_MATCH(), $config), $actual);
        self::assertEquals(2, count($this->eventProcessor->getEvents()));
    }

    public function test__feature_flag__when_evaluated_as_B_then_returns_true()
    {
        // given
        $experiment = Models::experiment(["key" => 42, "type" => ExperimentType::FEATURE_FLAG()]);
        $workspace = Models::workspace(["featureFlags" => [$experiment]]);
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);
        $config = new ParameterConfiguration(320, []);
        $evaluation = new ExperimentEvaluation(
            DecisionReason::TARGET_RULE_MATCH(),
            [],
            $experiment,
            2,
            "B",
            $config
        );
        $this->experimentEvaluator->method("evaluate")->willReturn($evaluation);
        $this->eventFactory->method("create")->willReturn([
            $this->createMock(UserEvent::class),
            $this->createMock(UserEvent::class)
        ]);

        // when
        $actual = $this->sut->featureFlag(42, $this->user);

        // then
        self::assertEquals(FeatureFlagDecision::on(DecisionReason::TARGET_RULE_MATCH(), $config), $actual);
        self::assertEquals(2, count($this->eventProcessor->getEvents()));
    }

    public function test__track__when_workspace_is_null_then_send_event_id_as_0()
    {
        // given
        $this->workspaceFetcher->method("fetch")->willReturn(null);

        // when
        $this->sut->track(HackleEvent::of("purchase"), $this->user);

        // then
        self::assertEquals(1, count($this->eventProcessor->getEvents()));
        $userEvent = $this->eventProcessor->getEvents()[0];
        self::assertInstanceOf(TrackEvent::class, $userEvent);
        self::assertEquals(0, $userEvent->getEventType()->getId());
    }

    public function test__track__when_event_type_is_null_then_send_event_id_as_0()
    {
        // given
        $workspace = Models::workspace();
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        // when
        $this->sut->track(HackleEvent::of("purchase"), $this->user);

        // then
        self::assertEquals(1, count($this->eventProcessor->getEvents()));
        $userEvent = $this->eventProcessor->getEvents()[0];
        self::assertInstanceOf(TrackEvent::class, $userEvent);
        self::assertEquals(0, $userEvent->getEventType()->getId());
    }

    public function test__track()
    {
        // given
        $eventType = new EventType(42, "purchase");
        $workspace = Models::workspace(["eventTypes" => [$eventType]]);
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        // when
        $this->sut->track(HackleEvent::of("purchase"), $this->user);

        // then
        self::assertEquals(1, count($this->eventProcessor->getEvents()));
        $userEvent = $this->eventProcessor->getEvents()[0];
        self::assertInstanceOf(TrackEvent::class, $userEvent);
        self::assertEquals(42, $userEvent->getEventType()->getId());
    }

    public function test__remote_config__when_workspace_is_null_then_returns_default_value()
    {
        // given
        $this->workspaceFetcher->method("fetch")->willReturn(null);

        // when
        $actual = $this->sut->remoteConfig(
            "parameter_key",
            $this->user,
            ValueType::STRING(),
            "default_input"
        );

        // then
        self::assertEquals(RemoteConfigDecision::of("default_input", DecisionReason::SDK_NOT_READY()), $actual);
        self::assertEquals(0, count($this->eventProcessor->getEvents()));
    }

    public function test__remote_config__when_parameter_not_found_then_returns_default_value()
    {
        // given
        $workspace = Models::workspace();
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        // when
        $actual = $this->sut->remoteConfig(
            "parameter_key",
            $this->user,
            ValueType::STRING(),
            "default_input"
        );

        // then
        self::assertEquals(
            RemoteConfigDecision::of("default_input", DecisionReason::REMOTE_CONFIG_PARAMETER_NOT_FOUND()),
            $actual
        );
        self::assertEquals(0, count($this->eventProcessor->getEvents()));
    }

    public function test__remote_config__evaluate()
    {
        // given
        $parameter = Models::parameter(["key" => "parameter_key"]);
        $workspace = Models::workspace(["remoteConfigParameters" => [$parameter]]);
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        $evaluation = new RemoteConfigEvaluation(
            DecisionReason::DEFAULT_RULE(),
            [],
            $parameter,
            42,
            "VVV",
            []
        );
        $this->remoteConfigEvaluator->method("evaluate")->willReturn($evaluation);
        $this->eventFactory->method("create")->willReturn([
            $this->createMock(UserEvent::class),
            $this->createMock(UserEvent::class)
        ]);

        // when
        $actual = $this->sut->remoteConfig(
            "parameter_key",
            $this->user,
            ValueType::STRING(),
            "default_input"
        );

        // then
        self::assertEquals(
            RemoteConfigDecision::of("VVV", DecisionReason::DEFAULT_RULE()),
            $actual
        );
        self::assertEquals(2, count($this->eventProcessor->getEvents()));
    }

    /*
     *       RC(1)
     *      /     \
     *     /       \
     *  AB(2)     FF(4)
     *    |   \     |
     *    |     \   |
     *  AB(3)     FF(5)
     *              |
     *              |
     *            AB(6)
     */
    public function testTargetExperiment()
    {
        $workspaceFetcher = new ResourcesWorkspaceFetcher(
            __DIR__ . "/../../Resources/workspace_target_experiment.json"
        );
        $eventProcessor = new InMemoryUserEventProcessor();
        $core = HackleCore::create($workspaceFetcher, $eventProcessor);

        $user = InternalHackleUser::builder()->identifier(IdentifierType::ID(), "user")->build();
        $decision = $core->remoteConfig("rc", $user, ValueType::STRING(), "42");

        $this->assertEquals(RemoteConfigDecision::of("Targeting!!", DecisionReason::TARGET_RULE_MATCH()), $decision);
        $this->assertCount(6, $eventProcessor->getEvents());

        $rootEvent = $eventProcessor->getEvents()[0];
        $this->assertInstanceOf(RemoteConfigEvent::class, $rootEvent);
        $this->assertEquals(
            array(
                "requestValueType" => "STRING",
                "requestDefaultValue" => "42",
                "targetRuleKey" => "rc_1_key",
                "targetRuleName" => "rc_1_name",
                "returnValue" => "Targeting!!"
            ),
            $rootEvent->getProperties()
        );

        foreach (array_slice($eventProcessor->getEvents(), 1) as $event) {
            $this->assertInstanceOf(ExposureEvent::class, $event);
            $this->assertEquals(
                array(
                    "\$targetingRootType" => "REMOTE_CONFIG",
                    "\$targetingRootId" => 1,
                    "\$experiment_version" => 1,
                    "\$execution_version" => 1
                ),
                $event->getProperties()
            );
        };
    }

    /*
     *     RC(1)
     *      ↓
     * ┌── AB(2)
     * ↑    ↓
     * |   FF(3)
     * ↑    ↓
     * |   AB(4)
     * └────┘
     */
    public function testExperimentCircular()
    {
        $workspaceFetcher = new ResourcesWorkspaceFetcher(
            __DIR__ . "/../../Resources/workspace_target_experiment_circular.json"
        );
        $eventProcessor = new InMemoryUserEventProcessor();
        $core = HackleCore::create($workspaceFetcher, $eventProcessor);

        $user = InternalHackleUser::builder()->identifier(IdentifierType::ID(), "user")->build();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Circular evaluation has occurred");
        $core->remoteConfig("rc", $user, ValueType::STRING(), "42");
    }

    /*
     *                     Container(1)
     * ┌──────────────┬───────────────────────────────────────┐
     * | ┌──────────┐ |                                       |
     * | |   AB(2)  | |                                       |
     * | └──────────┘ |                                       |
     * └──────────────┴───────────────────────────────────────┘
     *       25 %                        75 %
     */
    public function testContainer()
    {
        $workspaceFetcher = new ResourcesWorkspaceFetcher(
            __DIR__ . "/../../Resources/workspace_container.json"
        );
        $eventProcessor = new InMemoryUserEventProcessor();
        $core = HackleCore::create($workspaceFetcher, $eventProcessor);

        $decisions = [];
        for ($i = 0; $i < 10000; $i++) {
            $user = InternalHackleUser::builder()->identifier(IdentifierType::ID(), (string)$i)->build();
            $decision = $core->experiment(2, $user, "A");
            $decisions[] = $decision;
        }

        $this->assertCount(10000, $eventProcessor->getEvents());
        $this->assertCount(10000, $decisions);
        $this->assertCount(
            2452,
            array_filter($decisions, function (ExperimentDecision $decision): bool {
                return $decision->getReason() == DecisionReason::TRAFFIC_ALLOCATED;
            })
        );
        $this->assertCount(
            7548,
            array_filter($decisions, function (ExperimentDecision $decision): bool {
                return $decision->getReason() == DecisionReason::NOT_IN_MUTUAL_EXCLUSION_EXPERIMENT;
            })
        );
    }

    public function testSegmentMatch()
    {
        $workspaceFetcher = new ResourcesWorkspaceFetcher(
            __DIR__ . "/../../Resources/workspace_segment_match.json"
        );
        $eventProcessor = new InMemoryUserEventProcessor();
        $core = HackleCore::create($workspaceFetcher, $eventProcessor);

        $user1 = InternalHackleUser::builder()->identifier(IdentifierType::ID(), "matched_id")->build();
        $decision1 = $core->experiment(1, $user1, "A");
        $this->assertEquals(ExperimentDecision::of("A", DecisionReason::OVERRIDDEN()), $decision1);


        $user2 = InternalHackleUser::builder()->identifier(IdentifierType::ID(), "not_matched_id")->build();
        $decision2 = $core->experiment(1, $user2, "A");
        $this->assertEquals(ExperimentDecision::of("A", DecisionReason::TRAFFIC_ALLOCATED()), $decision2);
    }
}
