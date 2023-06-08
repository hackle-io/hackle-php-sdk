<?php

namespace Hackle\Internal\Model;

class Variation
{
    private $id;
    private $key;
    private $isDropped;
    private $parameterConfigurationId;

    /**
     * @param int $id
     * @param string $key
     * @param bool $isDropped
     * @param int|null $parameterConfigurationId
     */
    public function __construct(int $id, string $key, bool $isDropped, ?int $parameterConfigurationId)
    {
        $this->id = $id;
        $this->key = $key;
        $this->isDropped = $isDropped;
        $this->parameterConfigurationId = $parameterConfigurationId;
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
     * @return bool
     */
    public function isDropped(): bool
    {
        return $this->isDropped;
    }

    /**
     * @return int|null
     */
    public function getParameterConfigurationId(): ?int
    {
        return $this->parameterConfigurationId;
    }

    public static function from($data): Variation
    {
        return new Variation(
            $data["id"],
            $data["key"],
            $data["status"] === "DROPPED",
            $data["parameterConfigurationId"] ?? null
        );
    }
}
