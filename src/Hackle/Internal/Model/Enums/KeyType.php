<?php

namespace Hackle\Internal\Model\Enums;

use Hackle\Common\Enum;

class KeyType extends Enum
{
    const USER_ID = "USER_ID";
    const USER_PROPERTY = "USER_PROPERTY";
    const HACKLE_PROPERTY = "HACKLE_PROPERTY";
    const SEGMENT = "SEGMENT";
    const AB_TEST = "AB_TEST";
    const FEATURE_FLAG = "FEATURE_FLAG";
}
