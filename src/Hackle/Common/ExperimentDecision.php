<?php

namespace Hackle\Common;

class ExperimentDecision implements ParameterConfig
{
    /** @var string */
    private $variation;

    /** @var string */
    private $reason;

    /** @var ParameterConfig */
    private $config;

    /**
     * @param string $variation
     * @param string $reason
     * @param ParameterConfig $config
     */
    public function __construct(string $variation, string $reason, ParameterConfig $config)
    {
        $this->variation = $variation;
        $this->reason = $reason;
        $this->config = $config;
    }

    /**
     * @param string $variation
     * @param DecisionReason $reason
     * @param ParameterConfig|null $config
     * @return ExperimentDecision
     */
    public static function of(string $variation, DecisionReason $reason, ParameterConfig $config = null): self
    {
        return new ExperimentDecision($variation, $reason->getValue(), $config ?? new EmptyParameterConfig());
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
