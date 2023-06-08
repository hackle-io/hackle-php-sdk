<?php

namespace Hackle\Internal\Event;

use Hackle\Common\HackleEvent;
use Hackle\Internal\Event\Dispatcher\TrackEventDto;
use Hackle\Internal\Model\EventType;
use Hackle\Internal\User\IdentifierType;
use Hackle\Internal\User\InternalHackleUser;

class TrackEvent extends UserEvent
{
    private $eventType;
    private $event;

    public function __construct(
        string $insertId,
        int $timestamp,
        InternalHackleUser $user,
        EventType $eventType,
        HackleEvent $event
    ) {
        parent::__construct($insertId, $timestamp, $user);
        $this->eventType = $eventType;
        $this->event = $event;
    }

    public function toDto(): TrackEventDto
    {
        return new TrackEventDto(
            $this->getInsertId(),
            $this->getTimestamp(),
            $this->getUser()->getIdentifiers()[IdentifierType::ID] ?? null,
            $this->getUser()->getIdentifiers(),
            $this->getUser()->getProperties(),
            $this->getUser()->getHackleProperties(),
            $this->getEventType()->getId(),
            $this->getEventType()->getKey(),
            $this->getEvent()->getValue(),
            $this->getEvent()->getProperties()
        );
    }

    /**
     * @return EventType
     */
    public function getEventType(): EventType
    {
        return $this->eventType;
    }

    /**
     * @return HackleEvent
     */
    public function getEvent(): HackleEvent
    {
        return $this->event;
    }
}
