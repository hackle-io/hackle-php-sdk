<?php

namespace Hackle\Tests\Internal\Evaluation\Bucket;

use Hackle\Internal\Evaluation\Bucket\Bucketer;
use Hackle\Internal\Evaluation\Bucket\Murmur3Hash;
use PHPUnit\Framework\TestCase;

class BucketerTest extends TestCase
{

    public function testCalculateSlotNumber()
    {
        $this->verify("bucketing_all");
        $this->verify("bucketing_alphabetic");
        $this->verify("bucketing_alphanumeric");
        $this->verify("bucketing_numeric");
        $this->verify("bucketing_uuid");
    }

    private function verify(string $fileName)
    {
        $file = fopen(__DIR__ . "/../../../Resources/$fileName.csv", "r");
        $bucketer = new Bucketer(new Murmur3Hash());
        while (($line = fgets($file)) !== false) {
            $row = explode(",", rtrim($line));
            $seed = intval($row[0]);
            $slotSize = intval($row[1]);
            $value = $row[2];
            $slotNumber = $row[3];

            $this->assertEquals($slotNumber, $bucketer->calculateSlotNumber($seed, $slotSize, $value));
        }
    }
}
