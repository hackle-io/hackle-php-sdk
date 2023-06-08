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
    public const IN = "IN";
    public const CONTAINS = "CONTAINS";
    public const STARTS_WITH = "STARTS_WITH";
    public const ENDS_WITH = "ENDS_WITH";
    public const GT = "GT";
    public const GTE = "GTE";
    public const LT = "LT";
    public const LTE = "LTE";
}
