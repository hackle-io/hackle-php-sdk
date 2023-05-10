<?php

namespace Hackle\Common;

final class UserBuilder
{
    private $_id = null;
    private $_userId = null;
    private $_deviceId = null;
    private $_identifiers = [];
    private $_properties = [];

    public function id(?string $id): UserBuilder
    {
        $this->_id = $id;
        return $this;
    }

    public function userId(?string $userId): UserBuilder
    {
        $this->_userId = $userId;
        return $this;
    }

    public function deviceId(?string $deviceId): UserBuilder
    {
        $this->_deviceId = $deviceId;
        return $this;
    }

    public function identifier(string $type, ?string $value): UserBuilder
    {
        $this->_identifiers[$type] = $value;
        return $this;
    }

    public function identifiers(string $type, ?string $value): UserBuilder
    {
        $this->_identifiers[$type] = $value;
        return $this;
    }

    public function property(string $key, $value): UserBuilder
    {
        $this->_properties[$key] = $value;
        return $this;
    }
}
