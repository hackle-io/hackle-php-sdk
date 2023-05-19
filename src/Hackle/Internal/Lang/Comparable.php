<?php

namespace Hackle\Internal\Lang;

interface Comparable
{
    public function compareTo(Comparable $other): int;
}