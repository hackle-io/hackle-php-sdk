<?php

namespace Hackle\Internal\Evaluation\Evaluator;

use Hackle\Internal\Model\Experiment;

interface EvaluatorContext
{

    function getStack(): array;

    function getTargetEvaluations(): array;

    function contains(EvaluatorRequest $request): bool;

    function push(EvaluatorRequest $request);

    function pop();

    function get(Experiment $experiment): ?EvaluatorEvaluation;

    function add(EvaluatorEvaluation $evaluation);
//    /**
//     * @var EvaluatorRequest[]
//     */
//    private $_stack;
//
//    /**
//     * @var EvaluatorEvaluation[]
//     */
//    private $_evaluations;
//
//    public function __construct()
//    {
//        $this->_stack = [];
//        $this->_evaluations = [];
//    }
//
//    /**
//     * @return EvaluatorRequest[]
//     */
//    public function getStack(): array
//    {
//        return $this->_stack;
//    }
//
//    /**
//     * @return EvaluatorEvaluation[]
//     */
//    public function getTargetEvaluations(): array
//    {
//        return $this->_evaluations;
//    }
//
//
//    function contains(EvaluatorRequest $request): bool
//    {
//        return in_array($request, $this->_stack);
//    }
//
//    function push(EvaluatorRequest $request)
//    {
//        $this->_stack[] = $request;
//    }
//
//    function pop()
//    {
//        array_pop($this->_stack);
//    }
//
//    function get(Experiment $experiment): ?EvaluatorEvaluation
//    {
//        foreach ($this->_evaluations as $evaluation) {
//            if($evaluation instanceof ExperimentEvaluation and $evaluation.) {
//
//            }
//        }
//    }
}