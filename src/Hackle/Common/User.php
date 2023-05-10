<?php

namespace Hackle\Common;

final class User
{
    private $_id;

    private $_userId;

    private $_deviceId;

    private $_identifiers;

    private $properties;

    public function __construct(?string $id, ?string $userId, ?string $deviceId, array $identifiers, array $properties)
    {
        $this->_id = $id;
        $this->_userId = $userId;
        $this->_deviceId = $deviceId;
        $this->_identifiers = $identifiers;
        $this->properties = $properties;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->_id;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->_userId;
    }

    /**
     * @return string|null
     */
    public function getDeviceId(): ?string
    {
        return $this->_deviceId;
    }

    /**
     * @return array
     */
    public function getIdentifiers(): array
    {
        return $this->_identifiers;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public static function of(string $id): User
    {
        return self::builder()->id($id)->build();
    }

    public static function builder(): UserBuilder
    {
        return new UserBuilder();
    }
}
