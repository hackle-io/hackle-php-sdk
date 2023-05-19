<?php

namespace Hackle\Internal\Event;

use Hackle\Internal\User\HackleUser;

abstract class UserEvent
{
    private $insertId;
    private $timestamp;
    private $user;

    protected function __construct(string $insertId, int $timestamp, HackleUser $user)
    {
        $this->insertId = $insertId;
        $this->timestamp = $timestamp;
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getInsertId(): string
    {
        return $this->insertId;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return HackleUser
     */
    public function getUser(): HackleUser
    {
        return $this->user;
    }
}
