<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Lang\Enum;

/**
 * @method static IN()
 * @method static CONTAINS()
 * @method static STARTS_WITH()
 * @method static ENDS_WITH()
 * @method static GT()
 * @method static GTE()
 * @method static LT()
 * @method static LTE()
 */
class MatchOperator extends Enum
{
    const IN = "IN";
    const CONTAINS = "CONTAINS";
    const STARTS_WITH = "STARTS_WITH";
    const ENDS_WITH = "ENDS_WITH";
    const GT = "GT";
    const GTE = "GTE";
    const LT = "LT";
    const LTE = "LTE";
}
