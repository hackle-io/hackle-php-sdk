<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Utils\Arrays;

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
     * @param RemoteConfigTargetRule[] $targetRules
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

    public static function fromOrNull($data): ?RemoteConfigParameter
    {
        $type = ValueType::fromOrNull($data["type"]);
        if ($type === null) {
            return null;
        }
        return new RemoteConfigParameter(
            $data["id"],
            $data["key"],
            $type,
            $data["identifierType"],
            Arrays::mapNotNull($data["targetRules"], function ($data) {
                return RemoteConfigTargetRule::fromOrNull($data);
            }),
            new RemoteConfigParameterValue(
                $data["defaultValue"]["id"],
                $data["defaultValue"]["value"]
            )
        );
    }
}
