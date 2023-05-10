<?php

namespace Hackle\Common;

final class User
{
    public $id;

    public $userId;

    public $deviceId;

    public $identifiers;

    public $properties;

    /**
     * @param $id
     * @param $userId
     * @param $deviceId
     * @param $identifiers
     * @param $properties
     */
    public function __construct($id, $userId, $deviceId, $identifiers, $properties)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->deviceId = $deviceId;
        $this->identifiers = $identifiers;
        $this->properties = $properties;
    }
}
