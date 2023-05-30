<?php

namespace Hackle\Common;

class EmptyParameterConfig implements ParameterConfig
{
    public function getString(string $key, $defaultValue)
    {
        return $defaultValue;
    }

    public function getInt(string $key, $defaultValue)
    {
        return $defaultValue;
    }

    public function getFloat(string $key, $defaultValue)
    {
        return $defaultValue;
    }

    public function getBool(string $key, $defaultValue)
    {
        return $defaultValue;
    }

    public function __toString()
    {
        return "ParameterConfig({}";
    }
}
