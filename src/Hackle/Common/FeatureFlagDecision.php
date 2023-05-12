<?php

namespace Hackle\Common;

class FeatureFlagDecision implements ParameterConfig
{
    private $_on;

    /** @var DecisionReason */
    private $_reason;

    /** @var ParameterConfig */
    private $_config;

    public function __construct(bool $_on, DecisionReason $_reason, ParameterConfig $_config)
    {
        $this->_on = $_on;
        $this->_reason = $_reason;
        $this->_config = $_config;
    }

    public static function on(DecisionReason $reason, ParameterConfig $config): self
    {
        return new FeatureFlagDecision(true, $reason, $config);
    }

    public static function off(DecisionReason $reason, ParameterConfig $config): self
    {
        return new FeatureFlagDecision(false, $reason, $config);
    }

    /**
     * @return bool
     */
    public function isOn(): bool
    {
        return $this->_on;
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
}
