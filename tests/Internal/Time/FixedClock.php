<?php

namespace Hackle\Tests\Internal\Time;

use Hackle\Internal\Time\Clock;

class FixedClock implements Clock
{
    private $currentMillis;
    private $tick;

    public function __construct(int $currentMillis, int $tick)
    {
        $this->currentMillis = $currentMillis;
        $this->tick = $tick;
    }

    public function currentMillis(): int
    {
        return $this->currentMillis;
    }

    public function tick(): int
    {
        return $this->tick;
    }
}
