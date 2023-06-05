<?php

namespace Hackle\Tests\Internal\Client;

use Hackle\Common\DecisionReason;
use Hackle\Common\RemoteConfigDecision;
use Hackle\Common\User;
use Hackle\Internal\Client\HackleRemoteConfigImpl;
use Hackle\Internal\Core\HackleCore;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluator;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluation;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluator;
use Hackle\Internal\Event\UserEvent;
use Hackle\Internal\Event\UserEventFactory;
use Hackle\Internal\User\HackleUserResolver;
use Hackle\Internal\Workspace\WorkspaceFetcher;
use Hackle\Tests\Internal\Core\InMemoryUserEventProcessor;
use Hackle\Tests\Internal\Model\Models;
use Hackle\Tests\Internal\Time\FixedClock;
use http\Exception\InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class HackleRemoteConfigImplTest extends TestCase
{
    private $remoteConfigEvaluator;
    private $workspaceFetcher;
    private $eventFactory;
    private $core;

    private $logger;

    protected function setUp()
    {
        $this->remoteConfigEvaluator = $this->createMock(RemoteConfigEvaluator::class);
        $this->workspaceFetcher = $this->createMock(WorkspaceFetcher::class);
        $this->eventFactory = $this->createMock(UserEventFactory::class);

        $this->core = new HackleCore(
            $this->createMock(ExperimentEvaluator::class),
            $this->remoteConfigEvaluator,
            $this->workspaceFetcher,
            $this->eventFactory,
            new InMemoryUserEventProcessor(),
            new FixedClock(42, 320)
        );
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testInvalidUser()
    {
        $user = User::builder()->build();
        $parameter = Models::parameter();
        $workspace = Models::workspace(["remoteConfigParameters" => [$parameter]]);
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        $evaluation = new RemoteConfigEvaluation(
            DecisionReason::DEFAULT_RULE(),
            [],
            $parameter,
            42,
            "fail",
            []
        );
        $this->remoteConfigEvaluator->method("evaluate")->willReturn($evaluation);
        $this->eventFactory->method("create")->willReturn([
            $this->createMock(UserEvent::class),
            $this->createMock(UserEvent::class)
        ]);

        $sut = new HackleRemoteConfigImpl($user, $this->core, new HackleUserResolver(), $this->logger);
        $actual = $sut->getString("42", "default");

        self::assertEquals("default", $actual);
    }

    public function testString()
    {
        $user = User::builder()->id("user")->build();
        $parameter = Models::parameter(["key" => "42"]);
        $workspace = Models::workspace(["remoteConfigParameters" => [$parameter]]);
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        $evaluation = new RemoteConfigEvaluation(
            DecisionReason::DEFAULT_RULE(),
            [],
            $parameter,
            42,
            "string",
            []
        );
        $this->remoteConfigEvaluator->method("evaluate")->willReturn($evaluation);
        $this->eventFactory->method("create")->willReturn([
            $this->createMock(UserEvent::class),
            $this->createMock(UserEvent::class)
        ]);

        $sut = new HackleRemoteConfigImpl($user, $this->core, new HackleUserResolver(), $this->logger);
        $actual = $sut->getString("42", "default");

        self::assertSame("string", $actual);
    }

    public function testNumber()
    {
        $user = User::builder()->id("user")->build();
        $parameter = Models::parameter(["key" => "42"]);
        $workspace = Models::workspace(["remoteConfigParameters" => [$parameter]]);
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        $evaluation = new RemoteConfigEvaluation(
            DecisionReason::DEFAULT_RULE(),
            [],
            $parameter,
            42,
            42,
            []
        );
        $this->remoteConfigEvaluator->method("evaluate")->willReturn($evaluation);
        $this->eventFactory->method("create")->willReturn([
            $this->createMock(UserEvent::class),
            $this->createMock(UserEvent::class)
        ]);

        $sut = new HackleRemoteConfigImpl($user, $this->core, new HackleUserResolver(), $this->logger);

        self::assertSame(42, $sut->getInt("42", 320));
        self::assertSame(42.0, $sut->getFloat("42", 320.0));
    }

    public function testBoolean()
    {
        $user = User::builder()->id("user")->build();
        $parameter = Models::parameter(["key" => "42"]);
        $workspace = Models::workspace(["remoteConfigParameters" => [$parameter]]);
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        $evaluation = new RemoteConfigEvaluation(
            DecisionReason::DEFAULT_RULE(),
            [],
            $parameter,
            42,
            true,
            []
        );
        $this->remoteConfigEvaluator->method("evaluate")->willReturn($evaluation);
        $this->eventFactory->method("create")->willReturn([
            $this->createMock(UserEvent::class),
            $this->createMock(UserEvent::class)
        ]);

        $sut = new HackleRemoteConfigImpl($user, $this->core, new HackleUserResolver(), $this->logger);

        self::assertSame(true, $sut->getBool("42", false));
    }

    public function testException()
    {
        $user = User::builder()->id("user")->build();
        $parameter = Models::parameter(["key" => "42"]);
        $workspace = Models::workspace(["remoteConfigParameters" => [$parameter]]);
        $this->workspaceFetcher->method("fetch")->willReturn($workspace);

        $this->remoteConfigEvaluator->method("evaluate")->willThrowException(new \InvalidArgumentException());
        $sut = new HackleRemoteConfigImpl($user, $this->core, new HackleUserResolver(), $this->logger);

        self::assertSame("default", $sut->getString("42", "default"));
    }
}
