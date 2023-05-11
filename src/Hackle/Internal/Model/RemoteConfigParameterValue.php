<?php

namespace Hackle\Internal\Model;

class RemoteConfigParameterValue
{
    private $_id;

    /** @var mixed */
    private $_rawValue;

    public function __construct(int $_id, $_rawValue)
    {
        $this->_id = $_id;
        $this->_rawValue = $_rawValue;
    }

    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getRawValue()
    {
        return $this->_rawValue;
    }
}
