<?php

namespace Hackle\Internal\Model;

final class RemoteConfigTargetRule
{
    /**@var string */
    private $_key;

    /**@var string */
    private $_name;

    /** @var Target */
    private $_target;

    /**@var int */
    private $_bucketId;

    /** @var RemoteConfigParameterValue */
    private $_value;

    public function __construct(string $_key, string $_name, Target $_target, int $_bucketId, RemoteConfigParameterValue $_value)
    {
        $this->_key = $_key;
        $this->_name = $_name;
        $this->_target = $_target;
        $this->_bucketId = $_bucketId;
        $this->_value = $_value;
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
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @return Target
     */
    public function getTarget(): Target
    {
        return $this->_target;
    }

    /**
     * @return int
     */
    public function getBucketId(): int
    {
        return $this->_bucketId;
    }

    /**
     * @return RemoteConfigParameterValue
     */
    public function getValue(): RemoteConfigParameterValue
    {
        return $this->_value;
    }
}
