<?php

namespace Hackle\Internal\Workspace\Dto;

class BucketDto
{
    /**@var int */
    private $_id;

    /**@var int */
    private $_seed;

    /**@var int */
    private $_slotSize;

    /**@var SlotDto[] */
    private $_slots;

    /**
     * @param int $_id
     * @param int $_seed
     * @param int $_slotSize
     * @param int[] $_slots
     */
    public function __construct(int $_id, int $_seed, int $_slotSize, array $_slots)
    {
        $this->_id = $_id;
        $this->_seed = $_seed;
        $this->_slotSize = $_slotSize;
        $this->_slots = $_slots;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["id"], $v["seed"], $v["slotSize"], array_map(SlotDto::getDecoder(), $v["slots"]));
        };
    }

    public static function decode(array $v): self
    {
        $decoder = self::getDecoder();
        return $decoder($v);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * @return int
     */
    public function getSeed(): int
    {
        return $this->_seed;
    }

    /**
     * @return int
     */
    public function getSlotSize(): int
    {
        return $this->_slotSize;
    }

    /**
     * @return SlotDto[]
     */
    public function getSlots(): array
    {
        return $this->_slots;
    }
}
