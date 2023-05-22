<?php

namespace Internal\Event;

use Hackle\Internal\Event\Dispatcher\EventDispatcher;
use Hackle\Internal\Event\Processor\DefaultUserEventProcessor;
use Hackle\Internal\Event\UserEvent;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class DefaultEventProcessorTest extends TestCase
{
    private $eventDispatcher;

    /**@var DefaultUserEventProcessor */
    private $sut;

    protected function setUp()
    {
        $this->eventDispatcher = $this->createMock(EventDispatcher::class);
        $this->sut = new DefaultUserEventProcessor($this->eventDispatcher, 2, new Logger("Hackle"));
    }

    public function testEnqueue()
    {
        $event = $this->createMock(UserEvent::class);
        $this->sut->process($event);
        $this->assertEquals($this->sut->getQueue()[0], $event);
    }

    public function testFlushIfExceedCapacity()
    {
        $event1 = $this->createMock(UserEvent::class);
        $event2 = $this->createMock(UserEvent::class);
        $this->eventDispatcher->expects($this->once())->method("dispatch");
        $this->sut->process($event1);
        $this->sut->process($event2);
        self::assertEmpty($this->sut->getQueue());
    }

    public function testNotDispatchIfEmptyQueue()
    {
        $this->eventDispatcher->expects(self::never())->method("dispatch");
        $this->sut->flush();
    }

    public function testCallFlushIfDestructEventProcessor()
    {
        $event1 = $this->createMock(UserEvent::class);
        $this->sut->process($event1);
        $this->eventDispatcher->expects($this->once())->method("dispatch");
        $this->sut = null;
    }
}
