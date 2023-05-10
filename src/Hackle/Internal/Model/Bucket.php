<?php

namespace Hackle\Internal\Model;

class Bucket
{
    protected $id;
    protected $seed;
    protected $slotSize;

    private $_slots;

    /**
     * @param $id
     * @param $seed
     * @param $slotSize
     * @param $_slots
     */
    public function __construct($id, $seed, $slotSize, $_slots)
    {
        $this->id = $id;
        $this->seed = $seed;
        $this->slotSize = $slotSize;
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
