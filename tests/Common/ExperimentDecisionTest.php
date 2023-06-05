<?php

namespace Hackle\Tests\Common;

use Hackle\Common\DecisionReason;
use Hackle\Common\ExperimentDecision;
use Hackle\Common\Variation;
use PHPUnit\Framework\TestCase;

class ExperimentDecisionTest extends TestCase
{
    public function testDecision()
    {
        $decision = ExperimentDecision::of(Variation::A, DecisionReason::TRAFFIC_ALLOCATED());
        self::assertEquals(Variation::A, $decision->getVariation());
        self::assertEquals(DecisionReason::TRAFFIC_ALLOCATED(), $decision->getReason());
    }
}
