<?php

namespace Hackle\Internal\Event\Processor;

use Hackle\Internal\Event\Dispatcher\EventDispatcher;
use Hackle\Internal\Event\UserEvent;
use Psr\Log\LoggerInterface;

class EventProcessor
{
    /**@var EventDispatcher */
    private $_eventDispatcher;
    private $_queue = [];
    /** @var int */
    private $_capacity;

    /** @var LoggerInterface */
    private $_logger;

    /**
     * @param EventDispatcher $_eventDispatcher
     * @param array $_queue
     * @param int $_capacity
     * @param LoggerInterface $_logger
     */
    public function __construct(EventDispatcher $_eventDispatcher, array $_queue, int $_capacity, LoggerInterface $_logger)
    {
        $this->_eventDispatcher = $_eventDispatcher;
        $this->_queue = $_queue;
        $this->_capacity = $_capacity;
        $this->_logger = $_logger;
    }

    public function process(UserEvent $event)
    {
        $this->enqueue($event);
    }

    private function enqueue(UserEvent $message): void
    {
        $this->_queue[] = $message;
        if (count($this->_queue) >= $this->_capacity) {
            $this->flush();
        }
    }

    public function flush(): void
    {
        if (empty($this->_queue)) {
            return ;
        }
        $events = $this->_queue;
        $this->_queue = [];
        $this->_eventDispatcher->dispatch($events);
    }

    public function __destruct()
    {
        $this->flush();
    }
}
