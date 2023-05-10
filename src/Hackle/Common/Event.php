<?php

namespace Hackle\Common;

class Event
{
    private $_key;

    private $_value;

    private $_properties;

    public function __construct(string $key, ?float $value, array $properties)
    {
        $this->_key = $key;
        $this->_value = $value;
        $this->_properties = $properties;
    }

    public static function of(string $key): Event
    {
        return new Event($key, null, array());
    }

    public static function builder(string $key): EventBuilder
    {
        return new EventBuilder($key);
    }

    public function getKey(): string
    {
        return $this->_key;
    }

    public function getValue(): ?float
    {
        return $this->_value;
    }

    public function getProperties(): array
    {
        return $this->_properties;
    }
}
