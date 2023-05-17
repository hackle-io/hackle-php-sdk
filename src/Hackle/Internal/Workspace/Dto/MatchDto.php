<?php

namespace Hackle\Internal\Workspace\Dto;

class MatchDto
{
    /**@var string */
    private $_type;

    /**@var string */
    private $_operator;

    /**@var string */
    private $_valueType;

    /**@var array */
    private $_values;

    /**
     * @param string $_type
     * @param string $_operator
     * @param string $_valueType
     * @param array $_values
     */
    public function __construct(string $_type, string $_operator, string $_valueType, array $_values)
    {
        $this->_type = $_type;
        $this->_operator = $_operator;
        $this->_valueType = $_valueType;
        $this->_values = $_values;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["type"], $v["operator"], $v["valueType"], $v["values"]);
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
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->_operator;
    }

    /**
     * @return string
     */
    public function getValueType(): string
    {
        return $this->_valueType;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->_values;
    }
}
