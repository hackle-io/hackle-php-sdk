<?php

namespace Hackle\Internal\Lang;

/**
 * @template T1
 * @template T2
 */
final class Pair
{
    private $first;
    private $second;

    /**
     * @param T1 $first
     * @param T2 $second
     */
    public function __construct($first, $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    /**
     * @return T1
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @return T2
     */
    public function getSecond()
    {
        return $this->second;
    }
}
