<?php

namespace Hackle\Internal\Workspace;

class Sdk
{
    const VERSION = "1.0.0";
    const SDK_NAME = "php-sdk";

    /** @var string */
    private $key;

    /** @var string */
    private $name;

    /** @var string */
    private $version;
    
    /**
     * @param string $sdkKey
     */
    public function __construct(string $sdkKey)
    {
        $this->key = $sdkKey;
        $this->name = self::SDK_NAME;
        $this->version = self::VERSION;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
