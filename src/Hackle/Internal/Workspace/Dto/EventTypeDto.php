<?php

namespace Hackle\Internal\Workspace\Dto;
class EventTypeDto
{
    /**@var int */
    private $_id;

    /**@var string */
    private $_key;

    /**
     * @param int $_id
     * @param string $_key
     */
    public function __construct(int $_id, string $_key)
    {
        $this->_id = $_id;
        $this->_key = $_key;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["id"], $v["key"]);
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
}
