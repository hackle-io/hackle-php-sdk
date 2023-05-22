<?php

namespace Hackle\Internal\Model\Enums;

use Hackle\Internal\Lang\Enum;

/**
 * @method static AB_TEST()
 * @method static FEATURE_FLAG()
 */
class ExperimentType extends Enum
{
    public const AB_TEST = "AB_TEST";

    public const FEATURE_FLAG = "FEATURE_FLAG";
}