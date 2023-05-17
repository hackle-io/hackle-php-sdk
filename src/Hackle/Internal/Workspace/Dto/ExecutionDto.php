<?php

namespace Hackle\Internal\Workspace\Dto;

class ExecutionDto
{
    /**@var string */
    private $_status;

    /**@var UserOverrideDto[] */
    private $_userOverrides;

    /**@var TargetRuleDto[] */
    private $_segmentOverrides;

    /**@var TargetDto[] */
    private $_targetAudiences;

    /**@var TargetRuleDto[] */
    private $_targetRules;

    /**@var TargetActionDto */
    private $_defaultRule;

    /**
     * @param string $_status
     * @param UserOverrideDto[] $_userOverrides
     * @param TargetRuleDto[] $_segmentOverrides
     * @param TargetDto[] $_targetAudiences
     * @param TargetRuleDto[] $_targetRules
     * @param TargetActionDto $_defaultRule
     */
    public function __construct(string $_status, array $_userOverrides, array $_segmentOverrides, array $_targetAudiences, array $_targetRules, TargetActionDto $_defaultRule)
    {
        $this->_status = $_status;
        $this->_userOverrides = $_userOverrides;
        $this->_segmentOverrides = $_segmentOverrides;
        $this->_targetAudiences = $_targetAudiences;
        $this->_targetRules = $_targetRules;
        $this->_defaultRule = $_defaultRule;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["status"], array_map(UserOverrideDto::getDecoder(), $v["userOverrides"]), array_map(TargetRuleDto::getDecoder(), $v["segmentOverrides"]), array_map(TargetDto::getDecoder(), $v["targetAudiences"]), array_map(TargetRuleDto::getDecoder(), $v["targetRules"]), call_user_func(TargetActionDto::getDecoder(), $v["defaultRule"]));
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
    public function getStatus(): string
    {
        return $this->_status;
    }

    /**
     * @return UserOverrideDto[]
     */
    public function getUserOverrides(): array
    {
        return $this->_userOverrides;
    }

    /**
     * @return TargetRuleDto[]
     */
    public function getSegmentOverrides(): array
    {
        return $this->_segmentOverrides;
    }

    /**
     * @return TargetDto[]
     */
    public function getTargetAudiences(): array
    {
        return $this->_targetAudiences;
    }

    /**
     * @return TargetRuleDto[]
     */
    public function getTargetRules(): array
    {
        return $this->_targetRules;
    }

    /**
     * @return TargetActionDto
     */
    public function getDefaultRule(): TargetActionDto
    {
        return $this->_defaultRule;
    }
}
