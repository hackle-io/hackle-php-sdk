<?php

namespace Hackle\Internal\Evaluation\Evaluator;

use Hackle\Common\DecisionReason;

interface EvaluatorEvaluation
{

    function getReason(): DecisionReason;

    /**
     * @return EvaluatorEvaluation[]
     */
    function getTargetEvaluations(): array;
}