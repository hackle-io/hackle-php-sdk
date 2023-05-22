<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Utils\Arrays;

class Target
{
    private $conditions;

    /**
     * @param TargetCondition[] $conditions
     */
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @return TargetCondition[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    public static function fromOrNull($data, TargetingType $targetingType): ?Target
    {
        $conditions = Arrays::mapNotNull($data["conditions"], function ($data) use ($targetingType) {
            return TargetCondition::fromOrNull($data, $targetingType);
        });
        if (empty($conditions)) {
            return null;
        } else {
            return new Target($conditions);
        }
    }
}
