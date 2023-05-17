<?php

namespace Hackle\Internal\Workspace\Dto;

class TargetRuleDto
{
    /**@var TargetDto */
    private $_target;

    /**@var TargetActionDto */
    private $_action;

    public function __construct(TargetDto $_target, TargetActionDto $_action)
    {
        $this->_target = $_target;
        $this->_action = $_action;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self(
                call_user_func(TargetDto::getDecoder(), $v["target"]),
                call_user_func(TargetActionDto::getDecoder(), $v["action"])
            );
        };
    }

    public static function decode(array $v): self
    {
        $decoder = self::getDecoder();
        return $decoder($v);
    }

    /**
     * @return TargetDto
     */
    public function getTarget(): TargetDto
    {
        return $this->_target;
    }

    /**
     * @return TargetActionDto
     */
    public function getAction(): TargetActionDto
    {
        return $this->_action;
    }
}
