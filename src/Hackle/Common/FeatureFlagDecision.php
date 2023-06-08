<?php

namespace Hackle\Common;

class FeatureFlagDecision implements ParameterConfig
{
    /** @var bool */
    private $on;

    /** @var string */
    private $reason;

    /** @var ParameterConfig */
    private $config;

    /**
     * @param bool $on
     * @param string $reason
     * @param ParameterConfig $config
     */
    public function __construct(bool $on, string $reason, ParameterConfig $config)
    {
        $this->on = $on;
        $this->reason = $reason;
        $this->config = $config;
    }

    /**
     * @param DecisionReason $reason
     * @param ParameterConfig|null $config
     * @return FeatureFlagDecision
     */
    public static function on(DecisionReason $reason, ?ParameterConfig $config = null): self
    {
        return new FeatureFlagDecision(true, $reason->getValue(), $config ?? new EmptyParameterConfig());
    }

    /**
     * @param DecisionReason $reason
     * @param ParameterConfig|null $config
     * @return FeatureFlagDecision
     */
    public static function off(DecisionReason $reason, ?ParameterConfig $config = null): self
    {
        return new FeatureFlagDecision(false, $reason->getValue(), $config ?? new EmptyParameterConfig());
    }

    /**
     * @param string $key
     * @param string|mixed $defaultValue
     * @return mixed
     */
    public function getString(string $key, $defaultValue)
    {
        return $this->config->getString($key, $defaultValue);
    }

    /**
     * @param string $key
     * @param int|mixed $defaultValue
     * @return mixed
     */
    public function getInt(string $key, $defaultValue)
    {
        return $this->config->getInt($key, $defaultValue);
    }

    /**
     * @param string $key
     * @param float|mixed $defaultValue
     * @return mixed
     */
    public function getFloat(string $key, $defaultValue)
    {
        return $this->config->getFloat($key, $defaultValue);
    }

    /**
     * @param string $key
     * @param bool|mixed $defaultValue
     * @return mixed
     */
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
