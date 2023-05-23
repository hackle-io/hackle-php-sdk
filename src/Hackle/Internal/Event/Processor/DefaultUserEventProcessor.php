<?php

namespace Hackle\Internal\Event\Processor;

use Exception;
use Hackle\Internal\Event\Dispatcher\EventDispatcher;
use Hackle\Internal\Event\UserEvent;
use Psr\Log\LoggerInterface;

class DefaultUserEventProcessor implements UserEventProcessor
{
    /**@var EventDispatcher */
    private $eventDispatcher;
    private $queue = [];
    /** @var int */
    private $capacity;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param EventDispatcher $eventDispatcher
     * @param int $capacity
     * @param LoggerInterface $logger
     */
    public function __construct(EventDispatcher $eventDispatcher, int $capacity, LoggerInterface $logger)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->capacity = $capacity;
        $this->logger = $logger;
    }

    public function process(UserEvent $event)
    {
        $this->enqueue($event);
    }

    private function enqueue(UserEvent $event): void
    {
        $this->queue[] = $event;
        if (count($this->queue) >= $this->capacity) {
            $this->flush();
        }
    }

    public function flush(): void
    {
        if (empty($this->queue)) {
            return;
        }
        $events = $this->queue;
        $this->queue = [];
        try {
            $this->eventDispatcher->dispatch($events);
        } catch (Exception $e) {
            $this->logger->error("Failed to dispatch events : " . $e->getMessage());
        }
    }

    public function __destruct()
    {
        $this->flush();
    }

    /**
     * @return array
     */
    public function getQueue(): array
    {
        return $this->queue;
    }
}
