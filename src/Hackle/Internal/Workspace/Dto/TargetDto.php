<?php

namespace Hackle\Internal\Workspace\Dto;

class TargetDto
{
    /**@var ConditionDto[] */
    private $_conditions;

    /**
     * @param ConditionDto[] $_conditions
     */
    public function __construct(array $_conditions)
    {
        $this->_conditions = $_conditions;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self(array_map(ConditionDto::getDecoder(), $v["conditions"]));
        };
    }

    public static function decode(array $v): self
    {
        $decoder = self::getDecoder();
        return $decoder($v);
    }

    /**
     * @return ConditionDto[]
     */
    public function getConditions(): array
    {
        return $this->_conditions;
    }
}
