<?php

namespace Hackle\Internal\Model;

class RemoteConfigParameterValue
{
    private $id;
    private $rawValue;

    public function __construct(int $id, $rawValue)
    {
        $this->id = $id;
        $this->rawValue = $rawValue;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getRawValue()
    {
        return $this->rawValue;
    }
}
