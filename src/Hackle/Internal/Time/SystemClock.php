<?php

namespace Hackle\Internal\Time;

class SystemClock implements Clock
{
    public function currentMillis(): int
    {
        return (int)round(microtime(true) * 1000);
    }

    public function tick(): int
    {
        $milliseconds = $this->currentMillis();
        return $milliseconds * 1e6;
    }
}
