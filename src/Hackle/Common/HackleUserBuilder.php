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

    public function id(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function userId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function deviceId(?string $deviceId): self
    {
        $this->deviceId = $deviceId;
        return $this;
    }

    public function identifier(string $type, ?string $value): self
    {
        $this->identifiers->add($type, $value);
        return $this;
    }

    /** @param array<string, ?string> $identifiers */
    public function identifiers(?array $identifiers): self
    {
        if (!empty($identifiers)) {
            $this->identifiers->addAll($identifiers);
        }
        return $this;
    }

    public function property(string $key, $value): self
    {
        $this->properties->add($key, $value);
        return $this;
    }

    /** @param array<string, mixed> $properties */
    public function properties(?array $properties): self
    {
        if (!empty($properties)) {
            $this->properties->addAll($properties);
        }
        return $this;
    }

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
