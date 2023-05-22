<?php

namespace Internal\Core;

use Hackle\Common\DecisionReason;
use Hackle\Common\ExperimentDecision;
use Hackle\Common\RemoteConfigDecision;
use Hackle\Internal\Core\HackleCore;
use Hackle\Internal\Event\ExposureEvent;
use Hackle\Internal\Event\RemoteConfigEvent;
use Hackle\Internal\Model\Enums\ValueType;
use Hackle\Internal\User\HackleUser;
use Hackle\Internal\User\IdentifierType;
use Internal\Workspace\ResourcesWorkspaceFetcher;
use PHPUnit\Framework\TestCase;

require __DIR__ . "/InMemoryUserEventProcessor.php";
require __DIR__ . "/../Workspace/ResourcesWorkspaceFetcher.php";

class HackleCoreTest extends TestCase
{
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

        $user = HackleUser::builder()->identifier(IdentifierType::ID, "user")->build();
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
                    "\$targetingRootId" => 1
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

        $user = HackleUser::builder()->identifier(IdentifierType::ID, "user")->build();

        $this->setExpectedException(\InvalidArgumentException::class, "Circular evaluation has occurred");
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
            $user = HackleUser::builder()->identifier(IdentifierType::ID, (string)$i)->build();
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

        $user1 = HackleUser::builder()->identifier(IdentifierType::ID, "matched_id")->build();
        $decision1 = $core->experiment(1, $user1, "A");
        $this->assertEquals(ExperimentDecision::of("A", DecisionReason::OVERRIDDEN()), $decision1);


        $user2 = HackleUser::builder()->identifier(IdentifierType::ID, "not_matched_id")->build();
        $decision2 = $core->experiment(1, $user2, "A");
        $this->assertEquals(ExperimentDecision::of("A", DecisionReason::TRAFFIC_ALLOCATED()), $decision2);
    }
}
