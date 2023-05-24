<?php

namespace Hackle\Tests\Internal\Evaluation\Bucket;

use Hackle\Internal\Evaluation\Bucket\Bucketer;
use Hackle\Internal\Evaluation\Bucket\Murmur3Hash;
use Hackle\Internal\Model\Bucket;
use Hackle\Internal\Model\Slot;
use Mockery;
use PHPUnit\Framework\TestCase;

class BucketerTest extends TestCase
{

    public function test__bucketing()
    {
        // given
        $murmur3Hash = Mockery::mock(Murmur3Hash::class);
        $murmur3Hash->allows(["hash" => 42]);

        $sut = new Bucketer($murmur3Hash);

        $slot = new Slot(0, 100, 99);
        $bucket = new Bucket(
            320,
            1,
            10000,
            [$slot]
        );

        // when
        $actual = $sut->bucketing($bucket, "id");

        // then
        self::assertEquals($slot, $actual);
    }

    public function test__calculateSlotNumber()
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
