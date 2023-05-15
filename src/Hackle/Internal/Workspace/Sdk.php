<?php

namespace Hackle\Internal\Workspace;

class Sdk
{
    const VERSION = "1.0.0";
    const SDK_NAME = "php-sdk";
    private $_key;
    private $_name;
    private $_version;

    public function __construct(string $_sdkKey)
    {
        $this->_key = $_sdkKey;
        $this->_name = self::SDK_NAME;
        $this->_version = self::VERSION;
    }

    public function getKey(): string
    {
        return $this->_key;
    }

    public function getName(): string
    {
        return $this->_name;
    }

    public function getVersion(): string
    {
        return $this->_version;
    }
}
