<?php

namespace Hackle\Tests\Internal\Model;

use Hackle\Internal\Model\EventType;
use PHPUnit\Framework\TestCase;

class EventTypeTest extends TestCase
{
    public function testIdOfUndefinedIsZero()
    {
        $eventType = EventType::undefined("abc");
        self::assertEquals("abc", $eventType->getKey());
        self::assertEquals(0, $eventType->getId());
    }
}
