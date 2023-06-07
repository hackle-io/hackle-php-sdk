<?php

namespace Hackle\Common;

class HackleEventBuilder
{
    /** @var string */
    private $key;

    /** @var float|null */
    private $value;

    /** @var array */
    private $properties;

    /**
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->properties = new PropertiesBuilder();
        $this->key = $key;
    }

    /**
     * @param float|null $value
     * @return HackleEventBuilder
     */
    public function value(?float $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return HackleEventBuilder
     */
    public function property(string $key, $value): self
    {
        $this->properties->add($key, $value);
        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     * @return HackleEventBuilder
     */
    public function properties(?array $properties): self
    {
        if (!empty($properties)) {
            $this->properties->addAll($properties);
        }
        return $this;
    }

    /**
     * @return HackleEvent
     */
    public function build(): HackleEvent
    {
        return new HackleEvent($this->key, $this->value, $this->properties->build());
    }
}
