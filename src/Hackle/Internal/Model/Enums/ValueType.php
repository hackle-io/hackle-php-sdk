<?php

namespace Hackle\Internal\Model\Enums;

use Hackle\Internal\Lang\Enum;

/**
 * @method static STRING()
 * @method static NUMBER()
 * @method static BOOLEAN()
 * @method static VERSION()
 * @method static JSON()
 */
class ValueType extends Enum
{
    const STRING = "STRING";
    const NUMBER = "NUMBER";
    const BOOLEAN = "BOOLEAN";
    const VERSION = "VERSION";
    const JSON = "JSON";
}