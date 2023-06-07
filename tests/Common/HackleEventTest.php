<?php

namespace Hackle\Tests\Common;

use Hackle\Common\HackleEvent;
use PHPUnit\Framework\TestCase;

class HackleEventTest extends TestCase
{
    public function testBuild()
    {
        $event = HackleEvent::builder("purchase")
            ->value(42.0)
            ->property("k1", "v1")
            ->property("k2", 2)
            ->properties(array("k3" => true))
            ->properties(null)
            ->build();

        $this->assertEquals("purchase", $event->getKey());
        $this->assertEquals(42.0, $event->getValue());
        $this->assertEquals(array(
            "k1" => "v1",
            "k2" => 2,
            "k3" => true
        ), $event->getProperties());
    }

    public function testOf()
    {
        $event = HackleEvent::of("purchase");
        $this->assertEquals("purchase", $event->getKey());
        $this->assertEquals(null, $event->getValue());
        $this->assertEquals(array(), $event->getProperties());
    }
}
