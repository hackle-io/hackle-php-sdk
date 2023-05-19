<?php

namespace Hackle\Common;

class EmptyParameterConfig implements ParameterConfig
{
    public function getString(string $key, string $defaultValue): string
    {
        return $defaultValue;
    }

    public function getInt(string $key, int $defaultValue): int
    {
        return $defaultValue;
    }

    public function getFloat(string $key, float $defaultValue): float
    {
        return $defaultValue;
    }

    public function getBool(string $key, bool $defaultValue): bool
    {
        return $defaultValue;
    }

    public function __toString()
    {
        return "ParameterConfig({}";
    }
}
