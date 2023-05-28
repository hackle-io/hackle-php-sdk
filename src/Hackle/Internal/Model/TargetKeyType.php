<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Lang\Enum;

/**
 * @method static USER_ID()
 * @method static USER_PROPERTY()
 * @method static HACKLE_PROPERTY()
 * @method static SEGMENT()
 * @method static AB_TEST()
 * @method static FEATURE_FLAG()
 */
class TargetKeyType extends Enum
{
    const USER_ID = "USER_ID";
    const USER_PROPERTY = "USER_PROPERTY";
    const HACKLE_PROPERTY = "HACKLE_PROPERTY";
    const SEGMENT = "SEGMENT";
    const AB_TEST = "AB_TEST";
    const FEATURE_FLAG = "FEATURE_FLAG";
}
