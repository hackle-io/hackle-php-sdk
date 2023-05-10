<?php

namespace Hackle\Internal\Model;

class EventType
{
    private $_id;
    private $_key;

    public function __construct($_id, $_key)
    {
        $this->_id = $_id;
        $this->_key = $_key;
    }

    protected static function undefined(string $key): EventType
    {
        return new EventType(0, $key);
    }

    public function getId(): int
    {
        return $this->_id;
    }

    public function getKey(): string
    {
        return $this->_key;
    }
}
