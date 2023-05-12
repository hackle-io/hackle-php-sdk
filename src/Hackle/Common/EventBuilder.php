<?php

namespace Hackle\Common;

class EventBuilder
{
    private $_key;
    private $_value;
    private $_properties;

    public function __construct(string $_key)
    {
        $this->_properties = new PropertiesBuilder();
        $this->_key = $_key;
    }

    public function value(?float $value): EventBuilder
    {
        $this->_value = $value;
        return $this;
    }

    public function property(string $key, $value): EventBuilder
    {
        $this->_properties->add($key, $value);
        return $this;
    }

    public function properties(?array $properties): EventBuilder
    {
        if (!empty($properties)) {
            $this->_properties->addAll($properties);
        }
        return $this;
    }

    public function build(): Event
    {
        return new Event($this->_key, $this->_value, $this->_properties->build());
    }
}
