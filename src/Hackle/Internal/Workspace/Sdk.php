<?php

namespace Hackle\Internal\Workspace;

class Sdk
{
    const VERSION = "1.0.0";
    const SDK_NAME = "php-sdk";
    private $_key;
    private $_name;
    private $_version;

    private function __construct(string $_key, string $_name, string $_version)
    {
        $this->_key = $_key;
        $this->_name = $_name;
        $this->_version = $_version;
    }

    public static function load(string $sdkKey): Sdk
    {
        return new Sdk($sdkKey, self::SDK_NAME, self::VERSION);
    }
}
