<?php

namespace Hackle\Internal\Workspace\Dto;

class SegmentDto
{
    /**@var int */
    private $_id;

    /**@var string */
    private $_key;

    /**@var string */
    private $_type;

    /**@var TargetDto[] */
    private $_targets;

    /**
     * @param int $_id
     * @param string $_key
     * @param string $_type
     * @param TargetDto[] $_targets
     */
    public function __construct(int $_id, string $_key, string $_type, array $_targets)
    {
        $this->_id = $_id;
        $this->_key = $_key;
        $this->_type = $_type;
        $this->_targets = $_targets;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["id"], $v["key"], $v["type"], array_map(TargetDto::getDecoder(), $v["targets"]));
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
     * @return string
     */
    public function getKey(): string
    {
        return $this->_key;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * @return TargetDto[]
     */
    public function getTargets(): array
    {
        return $this->_targets;
    }
}
