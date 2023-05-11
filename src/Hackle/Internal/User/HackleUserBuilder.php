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

    public function identifiers(array $identifiers): HackleUserBuilder
    {
        $this->_identifiers->addAll($identifiers);
        return $this;
    }

    public function identifier(string $type, string $value, bool $overwrite = true): HackleUserBuilder
    {
        $this->_identifiers->add($type, $value, $overwrite);
        return $this;
    }

    public function properties(array $properties): HackleUserBuilder
    {
        $this->_properties->addAll($properties);
        return $this;
    }

    public function hackleProperties(array $hackleProperties): HackleUserBuilder
    {
        $this->_hackleProperties->addAll($hackleProperties);
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
