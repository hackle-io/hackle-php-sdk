<?php

namespace Hackle\Internal\Workspace\Dto;

class ConditionDto
{
    /**@var KeyDto */
    private $_key;

    /**@var MatchDto */
    private $_match;

    /**
     * @param KeyDto $_key
     * @param MatchDto $_match
     */
    public function __construct(KeyDto $_key, MatchDto $_match)
    {
        $this->_key = $_key;
        $this->_match = $_match;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self(call_user_func(KeyDto::getDecoder(), $v["key"]), call_user_func(MatchDto::getDecoder(), $v["match"]));
        };
    }

    public static function decode(array $v): self
    {
        $decoder = self::getDecoder();
        return $decoder($v);
    }

    /**
     * @return KeyDto
     */
    public function getKey(): KeyDto
    {
        return $this->_key;
    }

    /**
     * @return MatchDto
     */
    public function getMatch(): MatchDto
    {
        return $this->_match;
    }
}
