<?php

namespace Hackle\Internal\Event;

use Hackle\Common\Event;
use Hackle\Internal\Model\EventType;
use Hackle\Internal\User\HackleUser;

final class TrackEvent extends UserEvent
{
    private $eventType;
    private $event;

    public function __construct(string $insertId, int $timestamp, HackleUser $user, EventType $eventType, Event $event)
    {
        parent::__construct($insertId, $timestamp, $user);
        $this->eventType = $eventType;
        $this->event = $event;
    }

    /**
     * @return EventType
     */
    public function getEventType(): EventType
    {
        return $this->eventType;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }
}
