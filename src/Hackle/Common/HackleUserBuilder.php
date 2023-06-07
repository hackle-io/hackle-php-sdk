<?php

namespace Hackle\Common;

final class HackleUserBuilder
{
    /**@var string|null */
    private $id;

    /**@var string|null */
    private $userId;

    /**@var string|null */
    private $deviceId;

    /**@var IdentifiersBuilder */
    private $identifiers;

    /**@var PropertiesBuilder */
    private $properties;

    public function __construct()
    {
        $this->identifiers = new IdentifiersBuilder();
        $this->properties = new PropertiesBuilder();
    }

    /**
     * @param string|null $id
     * @return $this
     */
    public function id(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string|null $userId
     * @return $this
     */
    public function userId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param string|null $deviceId
     * @return $this
     */
    public function deviceId(?string $deviceId): self
    {
        $this->deviceId = $deviceId;
        return $this;
    }

    /**
     * @param string $type
     * @param string|null $value
     * @return HackleUserBuilder
     */
    public function identifier(string $type, ?string $value): self
    {
        $this->identifiers->add($type, $value);
        return $this;
    }

    /**
     * @param array<string, string> $identifiers
     * @return HackleUserBuilder
     */
    public function identifiers(?array $identifiers): self
    {
        if (!empty($identifiers)) {
            $this->identifiers->addAll($identifiers);
        }
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return HackleUserBuilder
     */
    public function property(string $key, $value): self
    {
        $this->properties->add($key, $value);
        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     * @return HackleUserBuilder
     */
    public function properties(?array $properties): self
    {
        if (!empty($properties)) {
            $this->properties->addAll($properties);
        }
        return $this;
    }

    /**
     * @return HackleUser
     */
    public function build(): HackleUser
    {
        return new HackleUser(
            $this->id,
            $this->userId,
            $this->deviceId,
            $this->identifiers->build(),
            $this->properties->build()
        );
    }
}
