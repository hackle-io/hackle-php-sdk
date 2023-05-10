<?php

namespace Hackle\Internal\Model;

class Slot
{
    private $_startInclusive;

    private $_endExclusive;

    protected $variationId;

    public function __construct($_startInclusive, $_endExclusive, $variationId)
    {
        $this->_startInclusive = $_startInclusive;
        $this->_endExclusive = $_endExclusive;
        $this->variationId = $variationId;
    }

    public function contains(int $slotNumber): bool
    {
        return $this->_startInclusive <= $slotNumber && $slotNumber < $this->_endExclusive;
    }
}
