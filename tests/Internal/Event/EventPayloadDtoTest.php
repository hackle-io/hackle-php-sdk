<?php

namespace Hackle\Tests\Internal\Event;

use Hackle\Internal\Event\Dispatcher\EventPayloadDto;
use Hackle\Internal\Event\Dispatcher\ExposureEventDto;
use Hackle\Internal\Event\Dispatcher\RemoteConfigEventDto;
use Hackle\Internal\Event\Dispatcher\TrackEventDto;
use Hackle\Internal\Event\ExposureEvent;
use Hackle\Internal\Event\RemoteConfigEvent;
use Hackle\Internal\Event\TrackEvent;
use PHPUnit\Framework\TestCase;

class EventPayloadDtoTest extends TestCase
{
    public function testToEventPayloadDto()
    {
        $exposureEvent = $this->createMock(ExposureEvent::class);
        $exposureEventDto = $this->createMock(ExposureEventDto::class);
        $exposureEvent->method("toDto")->willReturn($exposureEventDto);
        $remoteConfigEvent = $this->createMock(RemoteConfigEvent::class);
        $remoteConfigEventDto = $this->createMock(RemoteConfigEventDto::class);
        $remoteConfigEvent->method("toDto")->willReturn($remoteConfigEventDto);
        $trackEvent = $this->createMock(TrackEvent::class);
        $trackEventDto = $this->createMock(TrackEventDto::class);
        $trackEvent->method("toDto")->willReturn($trackEventDto);
        $events = array($exposureEvent, $remoteConfigEvent, $trackEvent);
        $expected = new EventPayloadDto(array($exposureEventDto), array($trackEventDto), array($remoteConfigEventDto));
        $actual = EventPayloadDto::toPayload($events);
        self::assertEquals($expected, $actual);
    }
}
