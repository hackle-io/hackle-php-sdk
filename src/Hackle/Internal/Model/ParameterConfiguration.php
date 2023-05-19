<?php

namespace Hackle\Internal\Model;

use Hackle\Common\ParameterConfig;

class ParameterConfiguration implements ParameterConfig
{
    /**@var int */
    private $_id;

    /**@var array */
    private $_parameters;

    public function __construct(int $_id, array $_parameters)
    {
        $this->_id = $_id;
        $this->_parameters = $_parameters;
    }

    public function getString(string $key, string $defaultValue): string
    {
        return strval($this->getOrNull($key)) ?? $defaultValue;
    }

    public function getInt(string $key, int $defaultValue): int
    {
        return intval($this->getOrNull($key)) ?? $defaultValue;
    }

    public function getFloat(string $key, float $defaultValue): float
    {
        return floatval($this->getOrNull($key)) ?? $defaultValue;
    }

    public function getBool(string $key, bool $defaultValue): bool
    {
        return boolval($this->getOrNull($key)) ?? $defaultValue;
    }

    private function getOrNull(string $key)
    {
        return $this->_parameters[$key] ?? null;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }
}
