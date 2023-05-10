<?php

namespace Hackle\Internal\Model;

class ContainerGroup
{
    private $_id;
    private $_experiments;

    public function __construct(int $id, array $experiments)
    {
        $this->_id = $id;
        $this->_experiments = $experiments;
    }
    public function getId(): int
    {
        return $this->_id;
    }
}
