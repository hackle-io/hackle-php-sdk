<?php

namespace Hackle\Internal\Evaluation\Evaluator\Experiment;

use Hackle\Internal\Evaluation\Evaluator\AbstractEvaluatorRequest;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorKey;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorType;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\User\HackleUser;
use Hackle\Internal\User\Workspace\Workspace;

final class ExperimentRequest implements EvaluatorRequest
{
    private $key;
    private $workspace;
    private $user;
    private $experiment;
    private $defaultVariationKey;

    public function __construct(
        Workspace $workspace,
        HackleUser $user,
        Experiment $experiment,
        string $defaultVariationKey
    ) {
        $this->key = new EvaluatorKey(new EvaluatorType(EvaluatorType::EXPERIMENT), $experiment->getId());
        $this->workspace = $workspace;
        $this->user = $user;
        $this->experiment = $experiment;
        $this->defaultVariationKey = $defaultVariationKey;
    }

    public static function of(
        Workspace $workspace,
        HackleUser $user,
        Experiment $experiment,
        string $defaultVariationKey
    ): ExperimentRequest {
        return new ExperimentRequest($workspace, $user, $experiment, $defaultVariationKey);
    }

    public static function fromRequest(EvaluatorRequest $request, Experiment $experiment): ExperimentRequest
    {
        return new ExperimentRequest($request->getWorkspace(), $request->getUser(), $experiment, "A");
    }

    /**
     * @return EvaluatorKey
     */
    public function getKey(): EvaluatorKey
    {
        return $this->key;
    }

    /**
     * @return Workspace
     */
    public function getWorkspace(): Workspace
    {
        return $this->workspace;
    }

    /**
     * @return HackleUser
     */
    public function getUser(): HackleUser
    {
        return $this->user;
    }

    /**
     * @return Experiment
     */
    public function getExperiment(): Experiment
    {
        return $this->experiment;
    }

    /**
     * @return string
     */
    public function getDefaultVariationKey(): string
    {
        return $this->defaultVariationKey;
    }


    public function __toString()
    {
        return "EvaluatorRequest(type={$this->experiment->getType()}, key={$this->experiment->getKey()})";
    }
}