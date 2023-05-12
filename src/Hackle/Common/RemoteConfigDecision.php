<?php

namespace Hackle\Common;

class RemoteConfigDecision
{
    private $_value;

    /**@var DecisionReason */
    private $_reason;

    private function __construct($_value, DecisionReason $_reason)
    {
        $this->_value = $_value;
        $this->_reason = $_reason;
    }

    /**
     * @param mixed $value
     */
    public static function of($value, DecisionReason $reason): self
    {
        return new RemoteConfigDecision($value, $reason);
    }
}
