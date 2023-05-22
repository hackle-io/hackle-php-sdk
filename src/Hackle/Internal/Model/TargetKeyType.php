<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Lang\Enum;


class TargetKeyType extends Enum
{
    const USER_ID = "USER_ID";
    const USER_PROPERTY = "USER_PROPERTY";
    const HACKLE_PROPERTY = "HACKLE_PROPERTY";
    const SEGMENT = "SEGMENT";
    const AB_TEST = "AB_TEST";
    const FEATURE_FLAG = "FEATURE_FLAG";
}