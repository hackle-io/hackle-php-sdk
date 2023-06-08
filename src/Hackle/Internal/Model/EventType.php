<?php

namespace Hackle\Internal\Model;

class EventType
{
    private $id;
    private $key;

    public function __construct(int $id, string $key)
    {
        $this->id = $id;
        $this->key = $key;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    public static function undefined(string $key): EventType
    {
        return new EventType(0, $key);
    }

    public static function from($data): EventType
    {
        return new EventType($data["id"], $data["key"]);
    }
}
