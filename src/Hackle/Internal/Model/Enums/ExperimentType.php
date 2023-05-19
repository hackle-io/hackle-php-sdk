<?php

namespace Hackle\Internal\Model\Enums;

use Hackle\Common\Enum;

class ExperimentType extends Enum
{
    public const AB_TEST = "AB_TEST";

    public const FEATURE_FLAG = "FEATURE_FLAG";
}