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

    public function isControl(): bool
    {
        return $this->value == Variation::A;
    }
}
