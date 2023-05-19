<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Model\Enums\ValueType;
use Hackle\Internal\User\IdentifierType;

class RemoteConfigParameter
{
    /**@var int */
    private $_id;

    /**@var string */
    private $_key;

    /** @var ValueType */
    private $_type;

    /** @var string */
    private $identifierType;

    /** @var RemoteConfigTargetRule[] */
    private $_targetRules;

    /** @var RemoteConfigParameterValue */
    private $_defaultValue;

    /**
     * @param int $_id
     * @param string $_key
     * @param ValueType $_type
     * @param string $identifierType
     * @param RemoteConfigTargetRule[] $_targetRules
     * @param RemoteConfigParameterValue $_defaultValue
     */
    public function __construct(int $_id, string $_key, ValueType $_type, string $identifierType, array $_targetRules, RemoteConfigParameterValue $_defaultValue)
    {
        $this->_id = $_id;
        $this->_key = $_key;
        $this->_type = $_type;
        $this->identifierType = $identifierType;
        $this->_targetRules = $_targetRules;
        $this->_defaultValue = $_defaultValue;
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
     * @return ValueType
     */
    public function getType(): ValueType
    {
        return $this->_type;
    }

    /**
     * @return string
     */
    public function getIdentifierType(): string
    {
        return $this->identifierType;
    }

    /**
     * @return RemoteConfigTargetRule[]
     */
    public function getTargetRules(): array
    {
        return $this->_targetRules;
    }

    /**
     * @return RemoteConfigParameterValue
     */
    public function getDefaultValue(): RemoteConfigParameterValue
    {
        return $this->_defaultValue;
    }
}
