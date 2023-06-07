<?php

namespace Hackle\Tests\Internal\Client;

use Hackle\Common\DecisionReason;
use Hackle\Common\RemoteConfigDecision;
use Hackle\Common\HackleUser;
use Hackle\Internal\Client\HackleRemoteConfigImpl;
use Hackle\Internal\Core\HackleCore;
use Hackle\Internal\User\HackleUserResolver;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class HackleRemoteConfigImplTest extends TestCase
{
    private $core;

    private $userResolver;

    private $logger;

    protected function setUp()
    {
        $this->core = $this->createMock(HackleCore::class);
        $this->userResolver = new HackleUserResolver();
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testInvalidUser()
    {
        $user = HackleUser::builder()->build();
        $sut = new HackleRemoteConfigImpl($user, $this->core, $this->userResolver, $this->logger);
        $actual = $sut->getString("42", "default");
        self::assertEquals("default", $actual);
    }

    public function testString()
    {
        $user = HackleUser::builder()->id("user")->build();
        $this->core
            ->method("remoteConfig")
            ->willReturn(RemoteConfigDecision::of("string", DecisionReason::DEFAULT_RULE()));

        $sut = new HackleRemoteConfigImpl($user, $this->core, $this->userResolver, $this->logger);
        self::assertSame("string", $sut->getString("42", "default"));
    }

    public function testNumber()
    {
        $user = HackleUser::builder()->id("user")->build();
        $this->core
            ->method("remoteConfig")
            ->willReturn(RemoteConfigDecision::of(42, DecisionReason::DEFAULT_RULE()));

        $sut = new HackleRemoteConfigImpl($user, $this->core, $this->userResolver, $this->logger);

        self::assertSame(42, $sut->getInt("42", 320));
        self::assertSame(42.0, $sut->getFloat("42", 320.0));
    }

    public function testBoolean()
    {
        $user = HackleUser::builder()->id("user")->build();
        $this->core
            ->method("remoteConfig")
            ->willReturn(RemoteConfigDecision::of(true, DecisionReason::DEFAULT_RULE()));

        $sut = new HackleRemoteConfigImpl($user, $this->core, $this->userResolver, $this->logger);

        self::assertSame(true, $sut->getBool("42", false));
    }

    public function testException()
    {
        $user = HackleUser::builder()->id("user")->build();
        $this->core->method("remoteConfig")->willThrowException(new \InvalidArgumentException());
        $sut = new HackleRemoteConfigImpl($user, $this->core, $this->userResolver, $this->logger);
        self::assertSame("default", $sut->getString("42", "default"));
    }
}
