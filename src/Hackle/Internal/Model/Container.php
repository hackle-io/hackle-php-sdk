<?php

namespace Hackle\Internal\Model;

class Container
{
    private $id;
    private $bucketId;
    private $groups;

    /**
     * @param int $id
     * @param int $bucketId
     * @param ContainerGroup[] $groups
     */
    public function __construct(int $id, int $bucketId, array $groups)
    {
        $this->id = $id;
        $this->bucketId = $bucketId;
        $this->groups = $groups;
    }


    public function getGroupOrNull(int $containerGroupId): ?ContainerGroup
    {
        foreach ($this->groups as $group) {
            if ($group->getId() === $containerGroupId) {
                return $group;
            }
        }
        return null;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getBucketId(): int
    {
        return $this->bucketId;
    }

    /**
     * @return ContainerGroup[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }


}
