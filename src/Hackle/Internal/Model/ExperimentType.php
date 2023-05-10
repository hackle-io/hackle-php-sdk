<?php

namespace Hackle\Internal\Model;

use Hackle\Common\Enum;

class ExperimentType extends Enum
{
    const AB_TEST = "AB_TEST";

    const FEATURE_FLAG = "FEATURE_FLAG";
}