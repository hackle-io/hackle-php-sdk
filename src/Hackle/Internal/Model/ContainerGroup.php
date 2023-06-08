<?php

namespace Hackle\Internal\Model;

class ContainerGroup
{
    private $id;
    private $experiments;

    /**
     * @param int $id
     * @param int[] $experiments
     */
    public function __construct(int $id, array $experiments)
    {
        $this->id = $id;
        $this->experiments = $experiments;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int[]
     */
    public function getExperiments(): array
    {
        return $this->experiments;
    }

    public static function from($data): ContainerGroup
    {
        return new ContainerGroup($data["id"], $data["experiments"]);
    }
}
