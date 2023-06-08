<?php

namespace Hackle\Internal\Evaluation\Evaluator;

use Hackle\Internal\Lang\Enum;

/**
 * @method static EXPERIMENT()
 * @method static REMOTE_CONFIG()
 */
class EvaluatorType extends Enum
{
    public const EXPERIMENT = "EXPERIMENT";
    public const REMOTE_CONFIG = "REMOTE_CONFIG";
}
