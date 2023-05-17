<?php

namespace Hackle\Internal\Workspace\Dto;

class KeyDto
{
    /**@var string */
    private $_type;

    /**@var string */
    private $_name;

    /**
     * @param string $_type
     * @param string $_name
     */
    public function __construct(string $_type, string $_name)
    {
        $this->_type = $_type;
        $this->_name = $_name;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["type"], $v["name"]);
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
    public function getName(): string
    {
        return $this->_name;
    }
}
