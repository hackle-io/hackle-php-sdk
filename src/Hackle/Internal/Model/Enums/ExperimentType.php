<?php

namespace Hackle\Internal\Model\Enums;

use Hackle\Internal\Lang\Enum;

class ExperimentType extends Enum
{
    public const AB_TEST = "AB_TEST";

    public const FEATURE_FLAG = "FEATURE_FLAG";
}