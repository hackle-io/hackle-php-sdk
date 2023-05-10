<?php

namespace Hackle\Internal\Model;

class Variation
{
    private $_id;
    private $_key;
    private $_isDropped;
    private $_parameterConfigurationId;
    public function getId()
    {
        return $this->_id;
    }
}
