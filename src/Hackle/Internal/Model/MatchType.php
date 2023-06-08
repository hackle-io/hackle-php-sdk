<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Lang\Enum;

/**
 * @method static MATCH()
 * @method static NOT_MATCH()
 */
final class MatchType extends Enum
{
    public const MATCH = "MATCH";
    public const NOT_MATCH = "NOT_MATCH";

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
