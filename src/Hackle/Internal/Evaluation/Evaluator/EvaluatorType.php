<?php

namespace Hackle\Internal\Evaluation\Evaluator;

use Hackle\Internal\Lang\Enum;

class EvaluatorType extends Enum
{
    const EXPERIMENT = "EXPERIMENT";
    const REMOTE_CONFIG = "REMOTE_CONFIG";
}