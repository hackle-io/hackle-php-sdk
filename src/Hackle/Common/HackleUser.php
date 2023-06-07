<?php

namespace Hackle\Common;

final class HackleUser
{
    /**@var string|null */
    private $id;

    /**@var string|null */
    private $userId;

    /**@var string|null */
    private $deviceId;

    /**@var array<string, string> */
    private $identifiers;

    /**@var array<string, mixed> */
    private $properties;

    /**
     * @param string|null $id
     * @param string|null $userId
     * @param string|null $deviceId
     * @param array<string, string> $identifiers
     * @param array<string, mixed> $properties
     */
    public function __construct(?string $id, ?string $userId, ?string $deviceId, array $identifiers, array $properties)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->deviceId = $deviceId;
        $this->identifiers = $identifiers;
        $this->properties = $properties;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @return string|null
     */
    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    /**
     * @return array<string, string>
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    /**
     * @return array<string, mixed>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public static function of(string $id): HackleUser
    {
        return self::builder()->id($id)->build();
    }

    public static function builder(): HackleUserBuilder
    {
        return new HackleUserBuilder();
    }
}
