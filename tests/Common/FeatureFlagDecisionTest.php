<?php

namespace Hackle\Tests\Common;

use Hackle\Common\DecisionReason;
use Hackle\Common\FeatureFlagDecision;
use PHPUnit\Framework\TestCase;

class FeatureFlagDecisionTest extends TestCase
{
    public function testOn()
    {
        $decision = FeatureFlagDecision::on(DecisionReason::DEFAULT_RULE());
        self::assertTrue($decision->isOn());
        self::assertSame(DecisionReason::DEFAULT_RULE, $decision->getReason());
    }

    public function testOff()
    {
        $decision = FeatureFlagDecision::off(DecisionReason::DEFAULT_RULE());
        self::assertFalse($decision->isOn());
        self::assertSame(DecisionReason::DEFAULT_RULE, $decision->getReason());
    }
}
