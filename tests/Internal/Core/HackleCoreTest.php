<?php

namespace Internal\Core;

use Hackle\Common\DecisionReason;
use Hackle\Common\RemoteConfigDecision;
use Hackle\Internal\Core\HackleCore;
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

        $userEventProcessor = new InMemoryUserEventProcessor();

        $core = HackleCore::create($workspaceFetcher, $userEventProcessor);

        $user = HackleUser::builder()->identifier(IdentifierType::ID, "user")->build();
        $decision = $core->remoteConfig("rc", $user, ValueType::STRING(), "42");

        $this->assertEquals(RemoteConfigDecision::of("Targeting!!", DecisionReason::TARGET_RULE_MATCH()), $decision);
    }

}
