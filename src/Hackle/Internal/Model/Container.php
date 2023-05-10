<?php

namespace Hackle\Internal\Model;

class Container
{
    private $_id;
    private $_bucketId;
    private $_groups;

    public function __construct(int $id, int $bucketId, array $groups)
    {
        $this->_id = $id;
        $this->_bucketId = $bucketId;
        $this->_groups = $groups;
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
}
