<?php

namespace Hackle\Common;

class Event
{
    private $key;
    private $value;
    private $properties;

    public function __construct(string $key, ?float $value, array $properties)
    {
        $this->key = $key;
        $this->value = $value;
        $this->properties = $properties;
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
        return $this->key;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}
