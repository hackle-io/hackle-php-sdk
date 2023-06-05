<?php

namespace Hackle\Common;

class EventBuilder
{
    private $key;
    private $value;
    private $properties;

    public function __construct(string $key)
    {
        $this->properties = new PropertiesBuilder();
        $this->key = $key;
    }

    public function value(?float $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function property(string $key, $value): self
    {
        $this->properties->add($key, $value);
        return $this;
    }

    public function properties(?array $properties): self
    {
        if (!empty($properties)) {
            $this->properties->addAll($properties);
        }
        return $this;
    }

    public function build(): Event
    {
        return new Event($this->key, $this->value, $this->properties->build());
    }
}
