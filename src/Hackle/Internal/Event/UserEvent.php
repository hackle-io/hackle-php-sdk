<?php

namespace Hackle\Internal\Event;
use Hackle\Internal\User\HackleUser;

abstract class UserEvent
{
    private $insertId;
    private $timestamp;

    private $_user;

    abstract function with(HackleUser $user): UserEvent;
}