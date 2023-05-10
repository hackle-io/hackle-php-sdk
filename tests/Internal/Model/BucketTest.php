<?php

namespace Internal\Model;

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

        $this->assertEquals($bucket->getSlotOrNull(0), $s1);
        $this->assertEquals($bucket->getSlotOrNull(99), $s1);
        $this->assertEquals($bucket->getSlotOrNull(100), $s2);
        $this->assertEquals($bucket->getSlotOrNull(199), $s2);
        $this->assertEquals($bucket->getSlotOrNull(200), null);
    }
}
