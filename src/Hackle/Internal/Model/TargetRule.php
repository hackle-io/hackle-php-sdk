<?php

namespace Hackle\Internal\Model;

class TargetRule
{
    private $target;
    private $action;

    public function __construct(Target $target, TargetAction $action)
    {
        $this->target = $target;
        $this->action = $action;
    }

    /**
     * @return Target
     */
    public function getTarget(): Target
    {
        return $this->target;
    }

    /**
     * @return TargetAction
     */
    public function getAction(): TargetAction
    {
        return $this->action;
    }

    public static function fromOrNull($date, TargetingType $targetingType): ?TargetRule
    {
        $target = Target::fromOrNull($date["target"], $targetingType);
        if ($target === null) {
            return null;
        }

        $action = TargetAction::fromOrNull($date["action"]);
        if ($action === null) {
            return null;
        }

        return new TargetRule($target, $action);
    }
}
