<?php

namespace Hackle\Common;

class RemoteConfigDecision
{
    private $value;
    private $reason;

    /**
     * @param mixed $value
     * @param string $reason
     */
    private function __construct($value, string $reason)
    {
        $this->value = $value;
        $this->reason = $reason;
    }

    /**
     * @param mixed $value
     * @param DecisionReason $reason
     * @return self
     */
    public static function of($value, DecisionReason $reason): self
    {
        return new RemoteConfigDecision($value, $reason->getValue());
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    public function __toString()
    {
        return "RemoteConfigDecision(value={$this->getValue()}, reason={$this->getReason()})";
    }
}
