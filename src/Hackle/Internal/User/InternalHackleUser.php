<?php

namespace Hackle\Internal\User;

class InternalHackleUser
{
    private $identifiers;
    private $properties;
    private $hackleProperties;

    /**
     * @param array<string, string> $identifiers
     * @param array<string, mixed> $properties
     * @param array<string, mixed> $hackleProperties
     */
    public function __construct(array $identifiers, array $properties, array $hackleProperties)
    {
        $this->identifiers = $identifiers;
        $this->properties = $properties;
        $this->hackleProperties = $hackleProperties;
    }

    public static function builder(): HackleUserBuilder
    {
        return new HackleUserBuilder();
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

    /**
     * @return array<string, mixed>
     */
    public function getHackleProperties(): array
    {
        return $this->hackleProperties;
    }
}
