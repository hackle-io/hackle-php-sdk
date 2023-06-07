<?php

namespace Hackle\Tests\Internal\Model;

use Hackle\Internal\Model\Slot;
use PHPUnit\Framework\TestCase;

class SlotTest extends TestCase
{
    public function testContains()
    {
        $slot = new Slot(1, 2, 3);
        self::assertFalse($slot->contains(0));
        self::assertTrue($slot->contains(1));
        self::assertFalse($slot->contains(2));
        self::assertFalse($slot->contains(4));
    }
}
