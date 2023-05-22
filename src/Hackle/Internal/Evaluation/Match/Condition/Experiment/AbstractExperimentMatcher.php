<?php

namespace Hackle\Internal\Evaluation\Match\Condition\Experiment;

use Hackle\Internal\Evaluation\Evaluator\Evaluator;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorEvaluation;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Match\Value\ValueOperatorMatcher;
use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\Condition;
use Hackle\Internal\Model\Experiment;

abstract class AbstractExperimentMatcher
{
    protected $evaluator;
    protected $valueOperatorMatcher;

    public function __construct(Evaluator $evaluator, ValueOperatorMatcher $valueOperatorMatcher)
    {
        $this->evaluator = $evaluator;
        $this->valueOperatorMatcher = $valueOperatorMatcher;
    }

    final public function matches(EvaluatorRequest $request, EvaluatorContext $context, Condition $condition): bool
    {
        $key = Objects::requireNotNull(
            Objects::asIntOrNull($condition->getKey()->getName()),
            "Invalid key [{$condition->getKey()->getType()}, {$condition->getKey()->getName()}]"
        );
        $experiment = $this->experiment($request, $key);
        $evaluation = $context->get($experiment) ?? $this->evaluate($request, $context, $experiment);
        Objects::require(
            $evaluation instanceof ExperimentEvaluation,
            "Unexpected evaluation [expected=ExperimentEvaluation, actual=$evaluation]"
        );
        return $this->evaluationMatches($evaluation, $condition);
    }

    private function evaluate(
        EvaluatorRequest $request,
        EvaluatorContext $context,
        Experiment $experiment
    ): EvaluatorEvaluation {
        $experimentRequest = ExperimentRequest::fromRequest($request, $experiment);
        $evaluation = $this->evaluator->evaluate($experimentRequest, $context);
        Objects::require(
            $evaluation instanceof ExperimentEvaluation,
            "Unexpected evaluation [expected=ExperimentEvaluation, actual=$evaluation]"
        );
        $resolvedEvaluation = $this->resolve($request, $evaluation);
        $context->add($resolvedEvaluation);
        return $resolvedEvaluation;
    }

    protected abstract function experiment(EvaluatorRequest $request, int $key): ?Experiment;

    protected abstract function resolve(
        EvaluatorRequest $request,
        ExperimentEvaluation $evaluation
    ): ExperimentEvaluation;

    protected abstract function evaluationMatches(ExperimentEvaluation $evaluation, Condition $condition): bool;
}