<?php

namespace Internal\Core;

use Hackle\Internal\Event\Processor\UserEventProcessor;
use Hackle\Internal\Event\UserEvent;

class InMemoryUserEventProcessor implements UserEventProcessor
{

    /**
     * @var UserEvent[]
     */
    private $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function process(UserEvent $event)
    {
        $this->events[] = $event;
    }

    /**
     * @return UserEvent[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }
}