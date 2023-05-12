<?php

namespace Hackle\Internal\Model;

class Slot
{
    private $_startInclusive;

    private $_endExclusive;

    private $_variationId;

    public function __construct($_startInclusive, $_endExclusive, $variationId)
    {
        $this->_startInclusive = $_startInclusive;
        $this->_endExclusive = $_endExclusive;
        $this->_variationId = $variationId;
    }

    public function contains(int $slotNumber): bool
    {
        return $this->_startInclusive <= $slotNumber && $slotNumber < $this->_endExclusive;
    }
}
