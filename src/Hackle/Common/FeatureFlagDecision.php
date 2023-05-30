<?php

namespace Hackle\Common;

class FeatureFlagDecision implements ParameterConfig
{
    private $on;
    private $reason;
    private $config;

    public function __construct(bool $on, string $reason, ParameterConfig $config)
    {
        $this->on = $on;
        $this->reason = $reason;
        $this->config = $config;
    }

    public static function on(DecisionReason $reason, ?ParameterConfig $config = null): self
    {
        return new FeatureFlagDecision(true, $reason->getValue(), $config ?? new EmptyParameterConfig());
    }

    public static function off(DecisionReason $reason, ?ParameterConfig $config = null): self
    {
        return new FeatureFlagDecision(false, $reason->getValue(), $config ?? new EmptyParameterConfig());
    }

    public function getString(string $key, $defaultValue)
    {
        return $this->config->getString($key, $defaultValue);
    }

    public function getInt(string $key, $defaultValue)
    {
        return $this->config->getInt($key, $defaultValue);
    }

    public function getFloat(string $key, $defaultValue)
    {
        return $this->config->getFloat($key, $defaultValue);
    }

    public function getBool(string $key, $defaultValue)
    {
        return $this->config->getBool($key, $defaultValue);
    }

    /**
     * @return bool
     */
    public function isOn(): bool
    {
        return $this->on;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
