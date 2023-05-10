<?php

namespace Hackle\Internal\Model;

use Hackle\Common\Enum;

class ValueType extends Enum
{
    const STRING = "STRING";
    const NUMBER = "NUMBER";
    const BOOLEAN = "BOOLEAN";
    const VERSION = "VERSION";
    const JSON = "JSON";
}