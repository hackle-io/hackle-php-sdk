<?php

namespace Hackle\Tests\Internal\Event;

use GuzzleHttp\Client;
use Hackle\Internal\Event\Dispatcher\EventDispatcher;
use Hackle\Internal\Event\Dispatcher\EventPayloadDto;
use Hackle\Internal\Event\Dispatcher\ExposureEventDto;
use Hackle\Internal\Event\Dispatcher\RemoteConfigEventDto;
use Hackle\Internal\Event\Dispatcher\TrackEventDto;
use Hackle\Internal\Event\ExposureEvent;
use Hackle\Internal\Event\RemoteConfigEvent;
use Hackle\Internal\Event\TrackEvent;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class EventDispatcherTest extends TestCase
{

    private const BASE_URI = "https://event.hackle.io";
    private const ENDPOINT = "/api/v2/events";

    public function testDispatch()
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

        $client = $this->createMock(Client::class);
        $options = array(
            "body" => json_encode(EventPayloadDto::toPayload($events)),
            "headers" => [
                "Content-type" => "application/json; charset=utf-8"
            ]
        );
        $client->expects(self::once())
            ->method("request")
            ->withConsecutive([
                $this->equalTo("POST"),
                $this->equalTo(self::BASE_URI . self::ENDPOINT),
                $this->equalTo($options)
            ]);

        $sut = new EventDispatcher(self::BASE_URI, $client, $this->createMock(LoggerInterface::class));
        $sut->dispatch($events);
    }

    public function testDispatchIfFail()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method("getStatusCode")->willReturn("301");

        $client = $this->createMock(Client::class);
        $client->method("request")->willReturn($response);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method("error")
            ->withConsecutive([$this->equalTo("Unexpected exception while submitting events for dispatch.")]);

        $sut = new EventDispatcher(self::BASE_URI, $client, $logger);
        $sut->dispatch(array());
    }
}
