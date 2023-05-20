<?php

namespace Hackle\Internal\Event\Dispatcher;

use Hackle\Internal\Event\ExposureEvent;
use Hackle\Internal\Event\RemoteConfigEvent;
use Hackle\Internal\Event\TrackEvent;

class EventPayloadDto
{
    /**@var ExposureEventDto[] */
    private $exposureEvents;

    /**@var TrackEventDto[] */
    private $trackEvents;

    /**@var RemoteConfigEventDto[] */
    private $remoteConfigEvents;

    /**
     * @param ExposureEventDto[] $exposureEvents
     * @param TrackEventDto[] $trackEvents
     * @param RemoteConfigEventDto[] $remoteConfigEvents
     */
    public function __construct(array $exposureEvents, array $trackEvents, array $remoteConfigEvents)
    {
        $this->exposureEvents = $exposureEvents;
        $this->trackEvents = $trackEvents;
        $this->remoteConfigEvents = $remoteConfigEvents;
    }

    public static function toPayload(array $userEvents): self
    {
        $exposures = [];
        $tracks = [];
        $remoteConfigEvents = [];
        foreach ($userEvents as $userEvent) {
            if ($userEvent instanceof ExposureEvent) {
                $exposures[] = $userEvent->toDto();
            } elseif ($userEvent instanceof TrackEvent) {
                $tracks[] = $userEvent->toDto();
            } elseif ($userEvent instanceof RemoteConfigEvent) {
                $remoteConfigEvents[] = $userEvent->toDto();
            }
        }
        return new EventPayloadDto($exposures, $tracks, $remoteConfigEvents);
    }
}
