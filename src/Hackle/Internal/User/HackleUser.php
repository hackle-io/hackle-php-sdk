<?php

namespace Hackle\Internal\User;

class HackleUser
{
    /** @var array */
    private $_identifiers;

    /** @var array */
    private $_properties;

    /** @var array */
    private $_hackleProperties;

    /**
     * @param array $_identifiers
     * @param array $_properties
     * @param array $_hackleProperties
     */
    public function __construct(array $_identifiers, array $_properties, array $_hackleProperties)
    {
        $this->_identifiers = $_identifiers;
        $this->_properties = $_properties;
        $this->_hackleProperties = $_hackleProperties;
    }

    public static function builder(): HackleUserBuilder
    {
        return new HackleUserBuilder();
    }

    public function getIdentifiers(): array
    {
        return $this->_identifiers;
    }

    public function getProperties(): array
    {
        return $this->_properties;
    }

    public function getHackleProperties(): array
    {
        return $this->_hackleProperties;
    }
}
