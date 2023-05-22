<?php

namespace Hackle\Internal\Evaluation\Evaluator;

use Hackle\Internal\Lang\Enum;

/**
 * @method static EXPERIMENT()
 * @method static REMOTE_CONFIG()
 */
class EvaluatorType extends Enum
{
    const EXPERIMENT = "EXPERIMENT";
    const REMOTE_CONFIG = "REMOTE_CONFIG";
}