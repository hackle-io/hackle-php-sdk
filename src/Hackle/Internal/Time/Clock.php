<?php

namespace Hackle\Internal\Time;

interface Clock
{
    public function currentMillis(): int;

    public function tick(): int;
}
