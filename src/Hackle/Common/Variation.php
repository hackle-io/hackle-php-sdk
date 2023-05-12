<?php

namespace Hackle\Common;

class Variation extends Enum
{
    const A = "A";
    const B = "B";
    const C = "C";
    const D = "D";
    const E = "E";
    const F = "F";
    const G = "G";
    const H = "H";
    const I = "I";
    const J = "J";

    public static function getControl(): self
    {
        return new Variation(Variation::A);
    }

    public function isControl(): bool
    {
        return $this == self::getControl();
    }

    public function isExperimental(): bool
    {
        return !$this->isControl();
    }
}
