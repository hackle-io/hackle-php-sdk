<?php

namespace Hackle\Internal\Event\Processor;

use Hackle\Internal\Event\UserEvent;

interface UserEventProcessor
{
    public function process(UserEvent $event);
}