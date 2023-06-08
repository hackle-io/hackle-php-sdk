<?php

namespace Hackle\Tests\Common;

use Hackle\Common\DecisionReason;
use Hackle\Common\RemoteConfigDecision;
use PHPUnit\Framework\TestCase;

class RemoteConfigDecisionTest extends TestCase
{
    public function testDecision()
    {
        $decision = RemoteConfigDecision::of("hello", DecisionReason::DEFAULT_RULE());
        self::assertEquals("hello", $decision->getValue());
        self::assertSame(DecisionReason::DEFAULT_RULE, $decision->getReason());
    }
}
