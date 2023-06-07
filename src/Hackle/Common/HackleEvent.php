<?php

namespace Hackle\Common;

class HackleEvent
{
    /** @var string */
    private $key;

    /** @var float|null */
    private $value;

    /** @var array<string, mixed> */
    private $properties;

    /**
     * @param string $key
     * @param float|null $value
     * @param array<string, mixed> $properties
     */
    public function __construct(string $key, ?float $value, array $properties)
    {
        $this->key = $key;
        $this->value = $value;
        $this->properties = $properties;
    }

    /**
     * @param string $key
     * @return HackleEvent
     */
    public static function of(string $key): HackleEvent
    {
        return new HackleEvent($key, null, array());
    }

    /**
     * @param string $key
     * @return HackleEventBuilder
     */
    public static function builder(string $key): HackleEventBuilder
    {
        return new HackleEventBuilder($key);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @return array<string, mixed>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
