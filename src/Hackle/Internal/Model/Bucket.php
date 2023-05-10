<?php

namespace Hackle\Internal\Model;

class Bucket
{
    private $_id;
    private $_seed;
    private $_slotSize;

    private $_slots;

    public function __construct(int $id, int $seed, int $slotSize, array $_slots)
    {
        $this->_id = $id;
        $this->_seed = $seed;
        $this->_slotSize = $slotSize;
        $this->_slots = $_slots;
    }

    public function getSlotOrNull(int $slotNumber): ?Slot
    {
        $slots = array_filter($this->_slots, function (Slot $slot) use ($slotNumber) {
            return $slot->contains($slotNumber);
        });
        if (empty($slots)) {
            return null;
        }
        return array_values($slots)[0];
    }
}
