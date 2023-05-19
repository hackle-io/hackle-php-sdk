<?php

namespace Hackle\Internal\Evaluation\Evaluator;

use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Model\Experiment;

final class EvaluatorContext
{
    private $requests;
    private $evaluations;

    public function contains(EvaluatorRequest $request): bool
    {
        return in_array($request, $this->requests);
    }

    public function push(EvaluatorRequest $request)
    {
        $this->requests[] = $this->requests;
    }

    public function pop()
    {
        array_pop($this->requests);
    }

    public function get(Experiment $experiment): ?ExperimentEvaluation
    {
        foreach ($this->evaluations as $evaluation) {
            if ($evaluation instanceof ExperimentEvaluation
                && $evaluation->getExperiment()->getId() === $experiment->getId()) {
                return $evaluation;
            }
        }
        return null;
    }

    public function add(EvaluatorEvaluation $evaluation)
    {
        $this->evaluations[] = $evaluation;
    }

    /**
     * @return array<EvaluatorRequest>
     */
    public function getStack(): array
    {
        return $this->requests;
    }

    /**
     * @return array<EvaluatorEvaluation>
     */
    public function getTargetEvaluations(): array
    {
        return $this->evaluations;
    }
}