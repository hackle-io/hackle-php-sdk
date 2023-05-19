<?php

namespace Hackle\Common;

class ExperimentDecision implements ParameterConfig
{
    private $variation;
    private $reason;
    private $config;

    private function __construct(string $variation, string $reason, ParameterConfig $config)
    {
        $this->variation = $variation;
        $this->reason = $reason;
        $this->config = $config;
    }

    public static function of(string $variation, DecisionReason $reason, ParameterConfig $config = null): self
    {
        return new ExperimentDecision($variation, $reason->getValue(), $config ?? new EmptyParameterConfig());
    }

    public function getString(string $key, string $defaultValue): string
    {
        return $this->config->getString($key, $defaultValue);
    }

    public function getInt(string $key, int $defaultValue): int
    {
        return $this->config->getInt($key, $defaultValue);
    }

    public function getFloat(string $key, float $defaultValue): float
    {
        return $this->config->getFloat($key, $defaultValue);
    }

    public function getBool(string $key, bool $defaultValue): bool
    {
        return $this->config->getBool($key, $defaultValue);
    }

    /**
     * @return string
     */
    public function getVariation(): string
    {
        return $this->variation;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
