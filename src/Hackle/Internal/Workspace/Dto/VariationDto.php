<?php

namespace Hackle\Internal\Workspace\Dto;

class VariationDto
{
    /**@var int */
    private $_id;

    /**@var string */
    private $_key;

    /**@var string */
    private $_status;

    /**@var ?int */
    private $_parameterConfigurationId;

    /**
     * @param int $_id
     * @param string $_key
     * @param string $_status
     * @param int|null $_parameterConfigurationId
     */
    public function __construct(int $_id, string $_key, string $_status, ?int $_parameterConfigurationId)
    {
        $this->_id = $_id;
        $this->_key = $_key;
        $this->_status = $_status;
        $this->_parameterConfigurationId = $_parameterConfigurationId;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new VariationDto($v["id"], $v["key"], $v["status"], $v["parameterConfigurationId"] ?? null);
        };
    }

    public static function decode(array $v): VariationDto
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
    public function getStatus(): string
    {
        return $this->_status;
    }

    /**
     * @return int|null
     */
    public function getParameterConfigurationId(): ?int
    {
        return $this->_parameterConfigurationId;
    }
}
