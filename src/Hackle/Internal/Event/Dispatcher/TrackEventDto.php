<?php

namespace Hackle\Internal\Event\Dispatcher;

use JsonSerializable;
use stdClass;

class TrackEventDto implements JsonSerializable
{
    /**@var string */
    private $insertId;

    /**@var int */
    private $timestamp;

    /**@var string|null */
    private $userId;

    /**@var array */
    private $identifiers;

    /**@var array */
    private $userProperties;

    /**@var array */
    private $hackleProperties;

    /**@var int */
    private $eventTypeId;

    /**@var string */
    private $eventTypeKey;

    /**@var float|null */
    private $value;

    /**@var array */
    private $properties;

    /**
     * @param string $insertId
     * @param int $timestamp
     * @param string|null $userId
     * @param array $identifiers
     * @param array $userProperties
     * @param array $hackleProperties
     * @param int $eventTypeId
     * @param string $eventTypeKey
     * @param float|null $value
     * @param array $properties
     */
    public function __construct(
        string $insertId,
        int $timestamp,
        ?string $userId,
        array $identifiers,
        array $userProperties,
        array $hackleProperties,
        int $eventTypeId,
        string $eventTypeKey,
        ?float $value,
        array $properties
    ) {
        $this->insertId = $insertId;
        $this->timestamp = $timestamp;
        $this->userId = $userId;
        $this->identifiers = $identifiers;
        $this->userProperties = $userProperties;
        $this->hackleProperties = $hackleProperties;
        $this->eventTypeId = $eventTypeId;
        $this->eventTypeKey = $eventTypeKey;
        $this->value = $value;
        $this->properties = $properties;
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
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    /**
     * @return array
     */
    public function getUserProperties(): array
    {
        return $this->userProperties;
    }

    /**
     * @return array
     */
    public function getHackleProperties(): array
    {
        return $this->hackleProperties;
    }

    /**
     * @return int
     */
    public function getEventTypeId(): int
    {
        return $this->eventTypeId;
    }

    /**
     * @return string
     */
    public function getEventTypeKey(): string
    {
        return $this->eventTypeKey;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function jsonSerialize(): array
    {
        return [
            "insertId" => $this->insertId,
            "timestamp" => $this->timestamp,
            "userId" => $this->userId,
            "identifiers" => empty($this->identifiers) ? new stdClass() : $this->identifiers,
            "userProperties" => empty($this->userProperties) ? new stdClass() : $this->userProperties,
            "hackleProperties" => empty($this->hackleProperties) ? new stdClass() : $this->hackleProperties,
            "eventTypeId" => $this->eventTypeId,
            "eventTypeKey" => $this->eventTypeKey,
            "value" => $this->value,
            "properties" => empty($this->properties) ? new stdClass() : $this->properties
        ];
    }
}
