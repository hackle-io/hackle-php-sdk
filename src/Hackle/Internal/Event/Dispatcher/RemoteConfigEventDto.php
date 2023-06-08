<?php

namespace Hackle\Internal\Event\Dispatcher;

use JsonSerializable;
use stdClass;

class RemoteConfigEventDto implements JsonSerializable
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
    private $parameterId;

    /**@var string */
    private $parameterKey;

    /**@var string */
    private $parameterType;

    /**@var string */
    private $decisionReason;

    /**@var int|null */
    private $valueId;

    /**@var array */
    private $properties;

    /**
     * @param string $insertId
     * @param int $timestamp
     * @param string|null $userId
     * @param array $identifiers
     * @param array $userProperties
     * @param array $hackleProperties
     * @param int $parameterId
     * @param string $parameterKey
     * @param string $parameterType
     * @param string $decisionReason
     * @param int|null $valueId
     * @param array $properties
     */
    public function __construct(
        string $insertId,
        int $timestamp,
        ?string $userId,
        array $identifiers,
        array $userProperties,
        array $hackleProperties,
        int $parameterId,
        string $parameterKey,
        string $parameterType,
        string $decisionReason,
        ?int $valueId,
        array $properties
    ) {
        $this->insertId = $insertId;
        $this->timestamp = $timestamp;
        $this->userId = $userId;
        $this->identifiers = $identifiers;
        $this->userProperties = $userProperties;
        $this->hackleProperties = $hackleProperties;
        $this->parameterId = $parameterId;
        $this->parameterKey = $parameterKey;
        $this->parameterType = $parameterType;
        $this->decisionReason = $decisionReason;
        $this->valueId = $valueId;
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
    public function getParameterId(): int
    {
        return $this->parameterId;
    }

    /**
     * @return string
     */
    public function getParameterKey(): string
    {
        return $this->parameterKey;
    }

    /**
     * @return string
     */
    public function getParameterType(): string
    {
        return $this->parameterType;
    }

    /**
     * @return string
     */
    public function getDecisionReason(): string
    {
        return $this->decisionReason;
    }

    /**
     * @return int|null
     */
    public function getValueId(): ?int
    {
        return $this->valueId;
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
            "identifiers" => empty($this->identifiers)? new stdClass() : $this->identifiers,
            "userProperties" => empty($this->userProperties)? new stdClass() : $this->userProperties,
            "hackleProperties" => empty($this->hackleProperties)? new stdClass() : $this->hackleProperties,
            "parameterId" => $this->parameterId,
            "parameterKey" => $this->parameterKey,
            "parameterType" => $this->parameterType,
            "decisionReason" => $this->decisionReason,
            "valueId" => $this->valueId,
            "properties" => empty($this->properties)? new stdClass() : $this->properties
        ];
    }
}
