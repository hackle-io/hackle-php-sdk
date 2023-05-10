<?php

namespace Hackle\Internal\Model;

use Hackle\Common\Enum;

class Operator extends Enum
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
