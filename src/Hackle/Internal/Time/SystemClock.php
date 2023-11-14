<?php

namespace Hackle\Internal\Time;

class SystemClock implements Clock
{
    public function currentMillis(): int
    {
        return (int)round(microtime(true) * 1e3);
    }

    public function tick(): int
    {
        return (int)round(microtime(true) * 1e9);
    }
}
