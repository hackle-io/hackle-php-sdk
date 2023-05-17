<?php

namespace Hackle\Internal\Workspace\Dto;

class ParameterDto
{
    /**@var string */
    private $_key;

    /**@var mixed */
    private $_value;

    /**
     * @param string $_key
     * @param mixed $_value
     */
    public function __construct(string $_key, $_value)
    {
        $this->_key = $_key;
        $this->_value = $_value;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["key"], $v["value"]);
        };
    }

    public static function decode(array $v): self
    {
        $decoder = self::getDecoder();
        return $decoder($v);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->_key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }
}
