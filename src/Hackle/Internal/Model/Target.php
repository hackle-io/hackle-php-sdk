<?php

namespace Hackle\Internal\Model;

class Target
{
    /** @var Condition[] */
    private $_conditions;

    public function __construct(array $_conditions)
    {
        $this->_conditions = $_conditions;
    }

    /**
     * @return array|Condition[]
     */
    public function getConditions(): array
    {
        return $this->_conditions;
    }
}
