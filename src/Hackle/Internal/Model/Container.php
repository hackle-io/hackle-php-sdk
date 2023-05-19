<?php

namespace Hackle\Internal\Model;

class Container
{
    /**@var int */
    private $_id;

    /**@var int */
    private $_bucketId;

    /** @var ContainerGroup[] */
    private $_groups;

    /**
     * @param int $_id
     * @param int $_bucketId
     * @param ContainerGroup[] $_groups
     */
    public function __construct(int $_id, int $_bucketId, array $_groups)
    {
        $this->_id = $_id;
        $this->_bucketId = $_bucketId;
        $this->_groups = $_groups;
    }

    public function getGroupOrNull(int $containerGroupId): ?ContainerGroup
    {
        $containerGroups = array_filter($this->_groups, function (ContainerGroup $containerGroup) use ($containerGroupId) {
            return $containerGroup->getId() == $containerGroupId;
        });
        if (empty($containerGroups)) {
            return null;
        }
        return array_values($containerGroups)[0];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * @return int
     */
    public function getBucketId(): int
    {
        return $this->_bucketId;
    }


    /**
     * @return array|ContainerGroup[]
     */
    public function getGroups(): array
    {
        return $this->_groups;
    }
}
