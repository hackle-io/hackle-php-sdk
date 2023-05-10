<?php

namespace Hackle\Common;

final class UserBuilder
{
    private $_id;
    private $_userId;
    private $_deviceId;
    private $_identifiers;
    private $_properties;

    public function __construct()
    {
        $this->_identifiers = new IdentifiersBuilder();
        $this->_properties = new PropertiesBuilder();
    }

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
        $this->_identifiers->add($type, $value);
        return $this;
    }

    public function identifiers(?array $identifiers): UserBuilder
    {
        if (!empty($identifiers)) {
            $this->_identifiers->addAll($identifiers);
        }
        return $this;
    }

    public function property(string $key, $value): UserBuilder
    {
        $this->_properties->add($key, $value);
        return $this;
    }

    public function properties(?array $properties): UserBuilder
    {
        if (!empty($properties)) {
            $this->_properties->addAll($properties);
        }
        return $this;
    }

    public function build(): User
    {
        return new User(
            $this->_id,
            $this->_userId,
            $this->_deviceId,
            $this->_identifiers->build(),
            $this->_properties->build()
        );
    }
}
