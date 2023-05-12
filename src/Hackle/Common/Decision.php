<?php

namespace Hackle\Common;

class Decision implements ParameterConfig
{
    /** @var Variation */
    private $_variation;

    /** @var DecisionReason */
    private $_reason;

    /** @var ParameterConfig */
    private $_config;

    public function __construct(Variation $_variation, DecisionReason $_reason, ParameterConfig $_config)
    {
        $this->_variation = $_variation;
        $this->_reason = $_reason;
        $this->_config = $_config;
    }

    public static function of(Variation $variation, DecisionReason $reason, ParameterConfig $config): self
    {
        return new Decision($variation, $reason, $config);
    }

    public function getString(string $key, string $defaultValue): string
    {
        return $this->_config->getString($key, $defaultValue);
    }

    public function getInt(string $key, int $defaultValue): int
    {
        return $this->_config->getInt($key, $defaultValue);
    }

    public function getFloat(string $key, float $defaultValue): float
    {
        return $this->_config->getFloat($key, $defaultValue);
    }

    public function getBool(string $key, bool $defaultValue): bool
    {
        return $this->_config->getBool($key, $defaultValue);
    }

    public function getVariation(): Variation
    {
        return $this->_variation;
    }

    public function getReason(): DecisionReason
    {
        return $this->_reason;
    }

    public function getConfig(): ParameterConfig
    {
        return $this->_config;
    }
}
