<?php

namespace Hackle\Internal\Model;

use Hackle\Common\ParameterConfig;
use Hackle\Internal\Lang\Pair;
use Hackle\Internal\Utils\Arrays;

class ParameterConfiguration implements ParameterConfig
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var array<string, mixed>
     */
    private $parameters;

    public function __construct(int $id, array $parameters)
    {
        $this->id = $id;
        $this->parameters = $parameters;
    }

    public function getString(string $key, $defaultValue)
    {
        $parameterValue = $this->get($key, $defaultValue);
        return is_string($parameterValue) ? $parameterValue : $defaultValue;
    }

    public function getInt(string $key, $defaultValue)
    {
        $parameterValue = $this->get($key, $defaultValue);
        return is_numeric($parameterValue) ? intval($parameterValue) : $defaultValue;
    }

    public function getFloat(string $key, $defaultValue)
    {
        $parameterValue = $this->get($key, $defaultValue);
        return is_numeric($parameterValue) ? floatval($parameterValue) : $defaultValue;
    }

    public function getBool(string $key, $defaultValue)
    {
        $parameterValue = $this->get($key, $defaultValue);
        return is_bool($parameterValue) ? $parameterValue : $defaultValue;
    }

    private function get(string $key, $defaultValue)
    {
        return $this->parameters[$key] ?? $defaultValue;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public static function from($data): ParameterConfiguration
    {
        return new ParameterConfiguration($data["id"], Arrays::associate($data["parameters"], function ($data) {
            return new Pair($data["key"], $data["value"]);
        }));
    }
}
