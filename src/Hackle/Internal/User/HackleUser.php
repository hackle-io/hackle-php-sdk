<?php

namespace Hackle\Internal\User;

class HackleUser
{
    /** @var array<string, string> */
    private $_identifiers;

    /** @var array<string, string> */
    private $_properties;

    /** @var array<string, string> */
    private $_hackleProperties;

    /**
     * @param string[] $_identifiers
     * @param string[] $_properties
     * @param string[] $_hackleProperties
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
