<?php

namespace Hackle\Internal\Model;

class Bucket
{
    private $id;
    private $seed;
    private $slotSize;
    private $slots;

    /**
     * @param int $id
     * @param int $seed
     * @param int $slotSize
     * @param Slot[] $slots
     */
    public function __construct(int $id, int $seed, int $slotSize, array $slots)
    {
        $this->id = $id;
        $this->seed = $seed;
        $this->slotSize = $slotSize;
        $this->slots = $slots;
    }

    public function getSlotOrNull(int $slotNumber): ?Slot
    {
        foreach ($this->slots as $slot) {
            if ($slot->contains($slotNumber)) {
                return $slot;
            }
        }
        return null;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSeed(): int
    {
        return $this->seed;
    }

    /**
     * @return int
     */
    public function getSlotSize(): int
    {
        return $this->slotSize;
    }

    /**
     * @return Slot[]
     */
    public function getSlots(): array
    {
        return $this->slots;
    }

    public static function from($data): Bucket
    {
        return new Bucket(
            $data["id"],
            $data["seed"],
            $data["slotSize"],
            array_map(function ($data) {
                return Slot::from($data);
            }, $data["slots"])
        );
    }
}
