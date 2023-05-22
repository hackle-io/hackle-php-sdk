<?php

namespace Hackle\Internal\Model;

class RemoteConfigParameter
{
    private $id;
    private $key;
    private $type;
    private $identifierType;
    private $targetRules;
    private $defaultValue;

    /**
     * @param int $id
     * @param string $key
     * @param string $type
     * @param string $identifierType
     * @param array<RemoteConfigTargetRule> $targetRules
     * @param RemoteConfigParameterValue $defaultValue
     */
    public function __construct(
        int $id,
        string $key,
        string $type,
        string $identifierType,
        array $targetRules,
        RemoteConfigParameterValue $defaultValue
    ) {
        $this->id = $id;
        $this->key = $key;
        $this->type = $type;
        $this->identifierType = $identifierType;
        $this->targetRules = $targetRules;
        $this->defaultValue = $defaultValue;
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

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getIdentifierType(): string
    {
        return $this->identifierType;
    }

    /**
     * @return RemoteConfigTargetRule[]
     */
    public function getTargetRules(): array
    {
        return $this->targetRules;
    }

    /**
     * @return RemoteConfigParameterValue
     */
    public function getDefaultValue(): RemoteConfigParameterValue
    {
        return $this->defaultValue;
    }
}
