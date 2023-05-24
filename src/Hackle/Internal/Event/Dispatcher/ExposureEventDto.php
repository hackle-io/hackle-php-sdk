<?php

namespace Hackle\Internal\Event\Dispatcher;

use JsonSerializable;
use stdClass;

class ExposureEventDto implements JsonSerializable
{
    /**@var string */
    private $insertId;

    /**@var int */
    private $timestamp;

    /**@var string */
    private $userId;

    /**@var array */
    private $identifiers;

    /**@var array */
    private $userProperties;

    /**@var array */
    private $hackleProperties;

    /**@var int */
    private $experimentId;

    /**@var int */
    private $experimentKey;

    /**@var string */
    private $experimentType;

    /**@var int */
    private $experimentVersion;

    /**@var int|null */
    private $variationId;

    /**@var string */
    private $variationKey;

    /**@var string */
    private $decisionReason;

    /**@var array */
    private $properties;

    /**
     * @param string $insertId
     * @param int $timestamp
     * @param string $userId
     * @param array $identifiers
     * @param array $userProperties
     * @param array $hackleProperties
     * @param int $experimentId
     * @param int $experimentKey
     * @param string $experimentType
     * @param int $experimentVersion
     * @param int|null $variationId
     * @param string $variationKey
     * @param string $decisionReason
     * @param array $properties
     */
    public function __construct(
        string $insertId,
        int $timestamp,
        string $userId,
        array $identifiers,
        array $userProperties,
        array $hackleProperties,
        int $experimentId,
        int $experimentKey,
        string $experimentType,
        int $experimentVersion,
        ?int $variationId,
        string $variationKey,
        string $decisionReason,
        array $properties
    ) {
        $this->insertId = $insertId;
        $this->timestamp = $timestamp;
        $this->userId = $userId;
        $this->identifiers = $identifiers;
        $this->userProperties = $userProperties;
        $this->hackleProperties = $hackleProperties;
        $this->experimentId = $experimentId;
        $this->experimentKey = $experimentKey;
        $this->experimentType = $experimentType;
        $this->experimentVersion = $experimentVersion;
        $this->variationId = $variationId;
        $this->variationKey = $variationKey;
        $this->decisionReason = $decisionReason;
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
     * @return string
     */
    public function getUserId(): string
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
    public function getExperimentId(): int
    {
        return $this->experimentId;
    }

    /**
     * @return int
     */
    public function getExperimentKey(): int
    {
        return $this->experimentKey;
    }

    /**
     * @return string
     */
    public function getExperimentType(): string
    {
        return $this->experimentType;
    }

    /**
     * @return int
     */
    public function getExperimentVersion(): int
    {
        return $this->experimentVersion;
    }

    /**
     * @return int|null
     */
    public function getVariationId(): ?int
    {
        return $this->variationId;
    }

    /**
     * @return string
     */
    public function getVariationKey(): string
    {
        return $this->variationKey;
    }

    /**
     * @return string
     */
    public function getDecisionReason(): string
    {
        return $this->decisionReason;
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
            "experimentId" => $this->experimentId,
            "experimentKey" => $this->experimentKey,
            "experimentType" => $this->experimentType,
            "variationId" => $this->variationId,
            "variationKey" => $this->variationKey,
            "decisionReason" => $this->decisionReason,
            "properties" => empty($this->properties)? new stdClass() : $this->properties
        ];
    }
}
