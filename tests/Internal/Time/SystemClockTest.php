<?php

namespace Hackle\Tests\Internal\Time;

use Hackle\Internal\Time\SystemClock;
use PHPUnit\Framework\TestCase;

class SystemClockTest extends TestCase
{

    public function test_currentMillis()
    {
        $sut = new SystemClock();

        $start = $sut->currentMillis();
        sleep(1);
        $end = $sut->currentMillis();

        self::assertThat(
            $end - $start,
            self::logicalAnd(
                $this->greaterThan(990),
                $this->lessThan(1010)
            )
        );
    }

    public function test_tick()
    {
        $sut = new SystemClock();

        $start = $sut->tick();
        sleep(1);
        $end = $sut->tick();

        self::assertThat(
            $end - $start,
            self::logicalAnd(
                $this->greaterThan(990000000),
                $this->lessThan(1010000000)
            )
        );
    }
}
