<?php

namespace Hackle\Internal\Workspace\Dto;

class RemoteConfigParameterValueDto
{
    /**@var int */
    private $_id;

    /**@var mixed */
    private $_value;

    /**
     * @param int $_id
     * @param mixed $_value
     */
    public function __construct(int $_id, $_value)
    {
        $this->_id = $_id;
        $this->_value = $_value;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["id"], $v["value"]);
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
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }
}
