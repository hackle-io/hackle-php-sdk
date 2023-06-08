<?php

namespace Hackle\Internal\Model;

class TargetCondition
{
    private $key;
    private $match;

    public function __construct(TargetKey $key, TargetMatch $match)
    {
        $this->key = $key;
        $this->match = $match;
    }

    /**
     * @return TargetKey
     */
    public function getKey(): TargetKey
    {
        return $this->key;
    }

    /**
     * @return TargetMatch
     */
    public function getMatch(): TargetMatch
    {
        return $this->match;
    }

    public static function fromOrNull($data, TargetingType $targetingType): ?TargetCondition
    {
        $key = TargetKey::fromOrNull($data["key"]);
        if ($key === null) {
            return null;
        }

        if (!$targetingType->supports($key->getType())) {
            return null;
        }

        $match = TargetMatch::fromOrNull($data["match"]);
        if ($match === null) {
            return null;
        }

        return new TargetCondition($key, $match);
    }
}
