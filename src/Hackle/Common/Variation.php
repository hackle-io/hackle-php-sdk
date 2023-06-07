<?php

namespace Hackle\Common;

class Variation
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

    /**
     * @return string
     */
    public static function getControl(): string
    {
        return Variation::A;
    }
}
