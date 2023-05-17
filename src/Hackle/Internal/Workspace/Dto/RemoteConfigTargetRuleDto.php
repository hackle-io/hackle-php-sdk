<?php

namespace Hackle\Internal\Workspace\Dto;

class RemoteConfigTargetRuleDto
{
    /**@var string */
    private $_key;

    /**@var string */
    private $_name;

    /**@var TargetDto */
    private $_target;

    /**@var int */
    private $_bucketId;

    /**@var RemoteConfigParameterValueDto */
    private $_value;

    /**
     * @param string $_key
     * @param string $_name
     * @param TargetDto $_target
     * @param int $_bucketId
     * @param RemoteConfigParameterValueDto $_value
     */
    public function __construct(string $_key, string $_name, TargetDto $_target, int $_bucketId, RemoteConfigParameterValueDto $_value)
    {
        $this->_key = $_key;
        $this->_name = $_name;
        $this->_target = $_target;
        $this->_bucketId = $_bucketId;
        $this->_value = $_value;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new RemoteConfigTargetRuleDto($v["key"], $v["name"], call_user_func(TargetDto::getDecoder(), $v["target"]), $v["bucketId"], call_user_func(RemoteConfigParameterValueDto::getDecoder(), $v["value"]));
        };
    }

    public static function decode(array $v): self
    {
        $decoder = self::getDecoder();
        return $decoder($v);
    }
}
