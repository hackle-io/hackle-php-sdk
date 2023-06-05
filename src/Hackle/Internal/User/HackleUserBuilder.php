<?php

namespace Hackle\Internal\User;

use Hackle\Common\IdentifiersBuilder;
use Hackle\Common\PropertiesBuilder;

class HackleUserBuilder
{
    /** @var IdentifiersBuilder */
    private $identifiers;

    /** @var PropertiesBuilder */
    private $properties;

    /** @var PropertiesBuilder */
    private $hackleProperties;

    public function __construct()
    {
        $this->identifiers = new IdentifiersBuilder();
        $this->properties = new PropertiesBuilder();
        $this->hackleProperties = new PropertiesBuilder();
    }

    public function identifiers(array $identifiers): self
    {
        $this->identifiers->addAll($identifiers);
        return $this;
    }

    public function identifier(IdentifierType $type, ?string $value, bool $overwrite = true): self
    {
        if ($value != null) {
            $this->identifiers->add($type->getValue(), $value, $overwrite);
        }
        return $this;
    }

    public function properties(array $properties): self
    {
        $this->properties->addAll($properties);
        return $this;
    }

    public function property(string $key, $value): self
    {
        $this->properties->add($key, $value);
        return $this;
    }

    public function hackleProperties(array $properties): self
    {
        $this->hackleProperties->addAll($properties);
        return $this;
    }

    public function hackleProperty(string $key, $value): self
    {
        $this->hackleProperties->add($key, $value);
        return $this;
    }

    public function build(): HackleUser
    {
        return new HackleUser(
            $this->identifiers->build(),
            $this->properties->build(),
            $this->hackleProperties->build()
        );
    }
}
