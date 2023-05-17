<?php

namespace Hackle\Internal\Workspace\Dto;

class ParameterConfigurationDto
{
    /**@var int */
    private $_id;

    /**@var ParameterDto[] */
    private $_parameters;

    /**
     * @param int $_id
     * @param ParameterDto[] $_parameters
     */
    public function __construct(int $_id, array $_parameters)
    {
        $this->_id = $_id;
        $this->_parameters = $_parameters;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["id"], array_map(ParameterDto::getDecoder(), $v["parameters"]));
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
     * @return ParameterDto[]
     */
    public function getParameters(): array
    {
        return $this->_parameters;
    }
}
