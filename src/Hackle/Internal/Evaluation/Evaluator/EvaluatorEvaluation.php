<?php

namespace Hackle\Internal\Evaluation\Evaluator;

use Hackle\Common\DecisionReason;

interface EvaluatorEvaluation
{

    public function getReason(): DecisionReason;

    /**
     * @return EvaluatorEvaluation[]
     */
    public function getTargetEvaluations(): array;
}
