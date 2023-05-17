<?php

namespace Hackle\Internal\Workspace\Dto;

class ContainerGroupDto
{

    /**@var int */
    private $_id;

    /**@var int[] */
    private $_experiments;

    /**
     * @param int $_id
     * @param int[] $_experiments
     */
    public function __construct(int $_id, array $_experiments)
    {
        $this->_id = $_id;
        $this->_experiments = $_experiments;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["id"], $v["experiments"]);
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
     * @return int[]
     */
    public function getExperiments(): array
    {
        return $this->_experiments;
    }
}
