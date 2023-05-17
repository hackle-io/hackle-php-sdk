<?php

namespace Hackle\Internal\Workspace\Dto;

class ContainerDto
{
    /**@var int */
    private $_id;

    /**@var int */
    private $_environmentId;

    /**@var int */
    private $_bucketId;

    /**@var ContainerGroupDto[] */
    private $_groups;

    /**
     * @param int $_id
     * @param int $_environmentId
     * @param int $_bucketId
     * @param ContainerGroupDto[] $_groups
     */
    public function __construct(int $_id, int $_environmentId, int $_bucketId, array $_groups)
    {
        $this->_id = $_id;
        $this->_environmentId = $_environmentId;
        $this->_bucketId = $_bucketId;
        $this->_groups = $_groups;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["id"], $v["environmentId"], $v["bucketId"], array_map(ContainerGroupDto::getDecoder(), $v["groups"]));
        };
    }

    public static function decode(array $v): self
    {
        $decoder = self::getDecoder();
        return $decoder($v);
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
    public function getEnvironmentId(): int
    {
        return $this->_environmentId;
    }

    /**
     * @return int
     */
    public function getBucketId(): int
    {
        return $this->_bucketId;
    }

    /**
     * @return ContainerGroupDto[]
     */
    public function getGroups(): array
    {
        return $this->_groups;
    }
}
