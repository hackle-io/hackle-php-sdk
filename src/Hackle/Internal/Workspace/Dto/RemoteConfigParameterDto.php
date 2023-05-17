<?php

namespace Hackle\Internal\Workspace\Dto;

class RemoteConfigParameterDto
{
    /**@var int */
    private $_id;

    /**@var string */
    private $_key;

    /**@var string */
    private $_type;

    /**@var string */
    private $_identifierType;

    /**@var RemoteConfigTargetRuleDto[] */
    private $_targetRules;

    /** @var RemoteConfigParameterValueDto */
    private $_defaultValue;

    /**
     * @param int $_id
     * @param string $_key
     * @param string $_type
     * @param string $_identifierType
     * @param RemoteConfigTargetRuleDto[] $_targetRules
     * @param RemoteConfigParameterValueDto $_defaultValue
     */
    public function __construct(int $_id, string $_key, string $_type, string $_identifierType, array $_targetRules, RemoteConfigParameterValueDto $_defaultValue)
    {
        $this->_id = $_id;
        $this->_key = $_key;
        $this->_type = $_type;
        $this->_identifierType = $_identifierType;
        $this->_targetRules = $_targetRules;
        $this->_defaultValue = $_defaultValue;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["id"], $v["key"], $v["type"], $v["identifierType"], array_map(RemoteConfigTargetRuleDto::getDecoder(), $v["targetRules"]), call_user_func(RemoteConfigParameterValueDto::getDecoder(), $v["defaultValue"]));
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
     * @return string
     */
    public function getIdentifierType(): string
    {
        return $this->_identifierType;
    }

    /**
     * @return RemoteConfigTargetRuleDto[]
     */
    public function getTargetRules(): array
    {
        return $this->_targetRules;
    }

    /**
     * @return RemoteConfigParameterValueDto
     */
    public function getDefaultValue(): RemoteConfigParameterValueDto
    {
        return $this->_defaultValue;
    }
}
