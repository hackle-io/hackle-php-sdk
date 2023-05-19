<?php

namespace Hackle\Internal\Event\Processor;

use Hackle\Internal\Event\UserEvent;

class EventMessage
{
    /**@var UserEvent */
    private $_event;

    /**
     * @param UserEvent $_event
     */
    public function __construct(UserEvent $_event)
    {
        $this->_event = $_event;
    }

    /**
     * @return UserEvent
     */
    public function getEvent(): UserEvent
    {
        return $this->_event;
    }
}
