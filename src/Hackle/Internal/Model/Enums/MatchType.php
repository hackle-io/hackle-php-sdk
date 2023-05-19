<?php

namespace Hackle\Internal\Model\Enums;

use Hackle\Common\Enum;

final class MatchType extends Enum
{
    const MATCH = "MATCH";
    const NOT_MATCH = "NOT_MATCH";

    public function matches(bool $isMatched): bool
    {
        switch ($this) {
            case self::MATCH:
                return $isMatched;
            case self::NOT_MATCH:
                return !$isMatched;
            default:
                return false;
        }
    }
}
