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
        $isProcessed = $this->produce(new EventMessage($event));
        if (!$isProcessed) {
            $this->_logger->warning("Event not processed, Exceeded event queue capacity");
        }
    }

    private function produce(EventMessage $message): bool
    {
        return $this->offer($message);
    }

    private function offer(EventMessage $message): bool
    {
        if (count($this->_queue) > $this->_capacity) {
            return false;
        }
        $this->_queue = $message;
        return true;
    }

    public function flush(): void
    {
        if (empty($this->_queue)) {
            return ;
        }
        $this->_eventDispatcher->dispatch(array());
    }

    public function __destruct()
    {
        $this->flush();
    }
}
