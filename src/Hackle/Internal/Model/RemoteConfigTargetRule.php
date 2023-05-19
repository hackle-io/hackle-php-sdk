<?php

namespace Hackle\Internal\Model;

final class RemoteConfigTargetRule
{
    private $key;
    private $name;
    private $target;
    private $bucketId;
    private $value;

    public function __construct(
        string $key,
        string $name,
        Target $target,
        int $bucketId,
        RemoteConfigParameterValue $value
    ) {
        $this->key = $key;
        $this->name = $name;
        $this->target = $target;
        $this->bucketId = $bucketId;
        $this->value = $value;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Target
     */
    public function getTarget(): Target
    {
        return $this->target;
    }

    /**
     * @return int
     */
    public function getBucketId(): int
    {
        return $this->bucketId;
    }

    /**
     * @return RemoteConfigParameterValue
     */
    public function getValue(): RemoteConfigParameterValue
    {
        return $this->value;
    }
}
