<?php


namespace Hackle\Internal;


final class HackleSdk
{
    private $key;
    private $name;
    private $version;

    /**
     * HackleSdk constructor.
     * @param string $key
     * @param string $name
     * @param string $version
     */
    public function __construct($key, $name, $version)
    {
        $this->key = $key;
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

}