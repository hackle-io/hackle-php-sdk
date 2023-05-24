<?php

namespace Hackle\Internal\User;

use Hackle\Common\IdentifiersBuilder;
use Hackle\Common\PropertiesBuilder;

class HackleUserBuilder
{
    /** @var IdentifiersBuilder */
    private $_identifiers;

    /** @var PropertiesBuilder */
    private $_properties;

    /** @var PropertiesBuilder */
    private $_hackleProperties;

    public function __construct()
    {
        $this->_identifiers = new IdentifiersBuilder();
        $this->_properties = new PropertiesBuilder();
        $this->_hackleProperties = new PropertiesBuilder();
    }

    public function identifiers(array $identifiers): self
    {
        $this->_identifiers->addAll($identifiers);
        return $this;
    }

    public function identifier(IdentifierType $type, ?string $value, bool $overwrite = true): self
    {
        if ($value != null) {
            $this->_identifiers->add($type->getValue(), $value, $overwrite);
        }
        return $this;
    }

    public function properties(array $properties): self
    {
        $this->_properties->addAll($properties);
        return $this;
    }

    public function build(): HackleUser
    {
        return new HackleUser(
            $this->_identifiers->build(),
            $this->_properties->build(),
            $this->_hackleProperties->build()
        );
    }
}
