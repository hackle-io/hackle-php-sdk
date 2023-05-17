<?php

namespace Hackle\Internal\Workspace\Dto;

class SlotDto
{
    /** @var  int */
    private $_startInclusive;

    /** @var  int */
    private $_endExclusive;

    /** @var  int */
    private $_variationId;

    /**
     * @param int $_startInclusive
     * @param int $_endExclusive
     * @param int $_variationId
     */
    public function __construct(int $_startInclusive, int $_endExclusive, int $_variationId)
    {
        $this->_startInclusive = $_startInclusive;
        $this->_endExclusive = $_endExclusive;
        $this->_variationId = $_variationId;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["startInclusive"], $v["endExclusive"], $v["variationId"]);
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
    public function getStartInclusive(): int
    {
        return $this->_startInclusive;
    }

    /**
     * @return int
     */
    public function getEndExclusive(): int
    {
        return $this->_endExclusive;
    }

    /**
     * @return int
     */
    public function getVariationId(): int
    {
        return $this->_variationId;
    }
}
