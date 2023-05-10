<?php

namespace Common;

use Hackle\Common\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testBuild()
    {
        $event = Event::builder("purchase")
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
        $event = Event::of("purchase");
        $this->assertEquals("purchase", $event->getKey());
        $this->assertEquals(null, $event->getValue());
        $this->assertEquals(array(), $event->getProperties());
    }
}
