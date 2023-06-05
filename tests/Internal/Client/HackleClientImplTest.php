<?php

namespace Hackle\Tests\Internal\Client;

use Hackle\Common\DecisionReason;
use Hackle\Common\Event;
use Hackle\Common\ExperimentDecision;
use Hackle\Common\FeatureFlagDecision;
use Hackle\Common\User;
use Hackle\Common\Variation;
use Hackle\Internal\Client\HackleClientImpl;
use Hackle\Internal\Core\HackleCore;
use Hackle\Internal\User\HackleUserResolver;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class HackleClientImplTest extends TestCase
{
    private $core;
    private $userResolver;
    private $sut;

    protected function setUp()
    {
        $this->core = $this->createMock(HackleCore::class);
        $this->userResolver = new HackleUserResolver();
        $this->sut = new HackleClientImpl($this->core, $this->userResolver, $this->createMock(LoggerInterface::class));
    }

    public function testVariation()
    {
        $user = User::of("42");
        $hackleUser = $this->userResolver->resolveOrNull($user);
        $this->core
            ->expects(self::once())
            ->method("experiment")
            ->withConsecutive([$this->equalTo(42), $this->equalTo($hackleUser), $this->equalTo(Variation::getControl())])
            ->willReturn(ExperimentDecision::of(Variation::G, DecisionReason::TRAFFIC_ALLOCATED()));

        $actual = $this->sut->variation(42, $user);
        self::assertEquals(Variation::G, $actual);
    }

    public function testVariationDetail()
    {
        $user = User::of("42");
        $hackleUser = $this->userResolver->resolveOrNull($user);
        $decision = ExperimentDecision::of(Variation::G, DecisionReason::TRAFFIC_ALLOCATED());
        $this->core
            ->expects(self::once())
            ->method("experiment")
            ->withConsecutive([$this->equalTo(42), $this->equalTo($hackleUser), $this->equalTo(Variation::getControl())])
            ->willReturn($decision);

        $actual = $this->sut->variationDetail(42, $user);
        self::assertSame($decision, $actual);
    }

    public function testVariationDetailIfOccurException()
    {
        $this->core->method("experiment")->willThrowException(new \InvalidArgumentException());
        $actual = $this->sut->variationDetail(42, User::of("42"));

        self::assertEquals(DecisionReason::EXCEPTION, $actual->getReason());
        self::assertSame(Variation::getControl(), $actual->getVariation());
    }

    public function testIsFeatureOn()
    {
        $user = User::of("42");
        $hackleUser = $this->userResolver->resolveOrNull($user);
        $this->core
            ->expects(self::once())
            ->method("featureFlag")
            ->withConsecutive([$this->equalTo(42), $this->equalTo($hackleUser)])
            ->willReturn(FeatureFlagDecision::on(DecisionReason::DEFAULT_RULE()));

        $actual = $this->sut->isFeatureOn(42, $user);
        self::assertTrue($actual);
    }

    public function testFeatureFlagDetail()
    {
        $user = User::of("42");
        $hackleUser = $this->userResolver->resolveOrNull($user);
        $decision = FeatureFlagDecision::on(DecisionReason::DEFAULT_RULE());
        $this->core
            ->expects(self::once())
            ->method("featureFlag")
            ->withConsecutive([$this->equalTo(42), $this->equalTo($hackleUser)])
            ->willReturn($decision);

        $actual = $this->sut->featureFlagDetail(42, $user);
        self::assertSame($decision, $actual);
    }

    public function testFeatureFlagDetailIfOccurException()
    {
        $this->core->method("featureFlag")->willThrowException(new \InvalidArgumentException());
        $actual = $this->sut->featureFlagDetail(42, User::of("42"));
        self::assertEquals(DecisionReason::EXCEPTION, $actual->getReason());
    }

    public function testTrack()
    {
        $user = User::of("42");
        $hackleUser = $this->userResolver->resolveOrNull($user);
        $event = Event::of("key");
        $this->core->expects(self::once())
            ->method("track")
            ->withConsecutive([$this->equalTo($event), $this->equalTo($hackleUser)]);
        $this->sut->track($event, $user);
    }
}
