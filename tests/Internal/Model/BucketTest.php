<?php

namespace Hackle\Tests;

use PHPUnit\Framework\TestCase;
use Hackle\Internal\Model\Bucket;
use Hackle\Internal\Model\Slot;

class BucketTest extends TestCase
{
    public function testGetSlotOrNum()
    {
        $s1 = new Slot(0, 100, 1);
        $s2 = new Slot(0, 200, 2);
        $bucket = new Bucket(1, 1, 10000, array($s1, $s2));

        $this->assertEquals($s1, $bucket->getSlotOrNull(0));
        $this->assertEquals($s1, $bucket->getSlotOrNull(99));
        $this->assertEquals($s2, $bucket->getSlotOrNull(100));
        $this->assertEquals($s2, $bucket->getSlotOrNull(199));
        $this->assertEquals(null, $bucket->getSlotOrNull(200));
    }
}
