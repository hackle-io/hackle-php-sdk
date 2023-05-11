<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Model\Enums\ValueType;

class RemoteConfigParameter
{
    private $_id;

    private $_key;

    /** @var ValueType */
    private $_type;

    /** @var RemoteConfigTargetRule[] */
    private $_targetRules;

    /** @var RemoteConfigParameterValue */
    private $_defaultValue;

    public function __construct($_id, $_key, ValueType $_type, array $_targetRules, RemoteConfigParameterValue $_defaultValue)
    {
        $this->_id = $_id;
        $this->_key = $_key;
        $this->_type = $_type;
        $this->_targetRules = $_targetRules;
        $this->_defaultValue = $_defaultValue;
    }
}
