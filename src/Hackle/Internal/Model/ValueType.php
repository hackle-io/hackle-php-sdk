<?php

namespace Hackle\Internal\Model;

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
    public const STRING = "STRING";
    public const NUMBER = "NUMBER";
    public const BOOLEAN = "BOOLEAN";
    public const VERSION = "VERSION";
    public const JSON = "JSON";
}