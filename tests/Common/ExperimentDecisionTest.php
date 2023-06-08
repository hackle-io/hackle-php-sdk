<?php

namespace Hackle\Tests\Common;

use Hackle\Common\DecisionReason;
use Hackle\Common\ExperimentDecision;
use PHPUnit\Framework\TestCase;

class ExperimentDecisionTest extends TestCase
{
    public function testDecision()
    {
        $decision = ExperimentDecision::of("A", DecisionReason::TRAFFIC_ALLOCATED());
        self::assertEquals("A", $decision->getVariation());
        self::assertEquals(DecisionReason::TRAFFIC_ALLOCATED(), $decision->getReason());
    }
}
