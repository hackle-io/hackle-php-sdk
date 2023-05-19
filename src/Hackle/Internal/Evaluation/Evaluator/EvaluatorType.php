<?php

namespace Hackle\Internal\Evaluation\Evaluator;

use Hackle\Common\Enum;

class EvaluatorType extends Enum
{
    const EXPERIMENT = "EXPERIMENT";
    const REMOTE_CONFIG = "REMOTE_CONFIG";
}