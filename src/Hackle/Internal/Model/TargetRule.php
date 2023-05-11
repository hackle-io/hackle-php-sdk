<?php

namespace Hackle\Internal\Model;

class TargetRule
{

    /** @var Target */
    private $_target;

    /** @var Action */
    private $_action;

    public function __construct(Target $_target, Action $_action)
    {
        $this->_target = $_target;
        $this->_action = $_action;
    }

    /**
     * @return Target
     */
    public function getTarget(): Target
    {
        return $this->_target;
    }

    /**
     * @return Action
     */
    public function getAction(): Action
    {
        return $this->_action;
    }
}
