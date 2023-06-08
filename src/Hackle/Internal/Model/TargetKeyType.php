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
    public const USER_ID = "USER_ID";
    public const USER_PROPERTY = "USER_PROPERTY";
    public const HACKLE_PROPERTY = "HACKLE_PROPERTY";
    public const SEGMENT = "SEGMENT";
    public const AB_TEST = "AB_TEST";
    public const FEATURE_FLAG = "FEATURE_FLAG";
}
