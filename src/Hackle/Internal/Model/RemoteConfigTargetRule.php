<?php

namespace Hackle\Internal\Model;

class RemoteConfigTargetRule
{
    private $_key;

    private $_name;

    /** @var Target */
    private $_target;

    private $_bucketId;

    /** @var RemoteConfigParameterValue */
    private $_value;

    public function __construct($_key, $_name, Target $_target, $_bucketId, RemoteConfigParameterValue $_value)
    {
        $this->_key = $_key;
        $this->_name = $_name;
        $this->_target = $_target;
        $this->_bucketId = $_bucketId;
        $this->_value = $_value;
    }
}
