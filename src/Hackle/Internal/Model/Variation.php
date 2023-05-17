<?php

namespace Hackle\Internal\Model;

class Variation
{
    /**@var int */
    private $_id;

    /**@var string */
    private $_key;

    /**@var bool */
    private $_isDropped;

    /**@var int|null */
    private $_parameterConfigurationId;

    /**
     * @param int $_id
     * @param string $_key
     * @param bool $_isDropped
     * @param int|null $_parameterConfigurationId
     */
    public function __construct(int $_id, string $_key, bool $_isDropped, ?int $_parameterConfigurationId)
    {
        $this->_id = $_id;
        $this->_key = $_key;
        $this->_isDropped = $_isDropped;
        $this->_parameterConfigurationId = $_parameterConfigurationId;
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
     * @return bool
     */
    public function isIsDropped(): bool
    {
        return $this->_isDropped;
    }

    /**
     * @return int|null
     */
    public function getParameterConfigurationId(): ?int
    {
        return $this->_parameterConfigurationId;
    }
}
