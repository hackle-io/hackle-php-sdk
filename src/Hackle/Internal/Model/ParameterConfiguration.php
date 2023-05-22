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
        return $this->parameters[$key] ?? null;
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
        return new ParameterConfiguration(
            $data["id"],
            Arrays::associate($data["parameters"], function ($data) {
                return new Pair($data["key"], $data["value"]);
            })
        );
    }
}
