<?php

namespace Hackle\Internal\Evaluation\Evaluator\Experiment;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorEvaluation;
use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\Model\ParameterConfiguration;
use Hackle\Internal\Model\Variation;

final class ExperimentEvaluation implements EvaluatorEvaluation
{

    private $reason;
    private $targetEvaluations;
    private $experiment;
    private $variationId;
    private $variationKey;
    private $config;

    /**
     * @param DecisionReason $reason
     * @param EvaluatorEvaluation[] $targetEvaluations
     * @param Experiment $experiment
     * @param int|null $variationId
     * @param string $variationKey
     * @param ParameterConfiguration|null $config
     */
    public function __construct(
        DecisionReason $reason,
        array $targetEvaluations,
        Experiment $experiment,
        ?int $variationId,
        string $variationKey,
        ?ParameterConfiguration $config
    ) {
        $this->reason = $reason;
        $this->targetEvaluations = $targetEvaluations;
        $this->experiment = $experiment;
        $this->variationId = $variationId;
        $this->variationKey = $variationKey;
        $this->config = $config;
    }

    public static function of(
        ExperimentRequest $request,
        EvaluatorContext $context,
        Variation $variation,
        DecisionReason $reason
    ): ExperimentEvaluation {
        $configId = $variation->getParameterConfigurationId();
        $config = null;
        if ($configId !== null) {
            $config = Objects::requireNotNull(
                $request->getWorkspace()->getParameterConfigurationOrNull($configId),
                "ParameterConfiguration[$configId]"
            );
        }

        return new ExperimentEvaluation(
            $reason,
            $context->getTargetEvaluations(),
            $request->getExperiment(),
            $variation->getId(),
            $variation->getKey(),
            $config
        );
    }

    public static function ofDefault(
        ExperimentRequest $request,
        EvaluatorContext $context,
        DecisionReason $reason
    ): ExperimentEvaluation {
        $variation = $request->getExperiment()->getVariationOrNullByKey($request->getDefaultVariationKey());
        if ($variation !== null) {
            return ExperimentEvaluation::of($request, $context, $variation, $reason);
        } else {
            return new ExperimentEvaluation(
                $reason,
                $context->getTargetEvaluations(),
                $request->getExperiment(),
                null,
                $request->getDefaultVariationKey(),
                null
            );
        }
    }

    public function with(DecisionReason $reason): ExperimentEvaluation
    {
        return new ExperimentEvaluation(
            $reason,
            $this->targetEvaluations,
            $this->experiment,
            $this->variationId,
            $this->variationKey,
            $this->config
        );
    }

    /**
     * @return DecisionReason
     */
    public function getReason(): DecisionReason
    {
        return $this->reason;
    }

    /**
     * @return EvaluatorEvaluation[]
     */
    public function getTargetEvaluations(): array
    {
        return $this->targetEvaluations;
    }

    /**
     * @return Experiment
     */
    public function getExperiment(): Experiment
    {
        return $this->experiment;
    }

    /**
     * @return int|null
     */
    public function getVariationId(): ?int
    {
        return $this->variationId;
    }

    /**
     * @return string
     */
    public function getVariationKey(): string
    {
        return $this->variationKey;
    }

    /**
     * @return ParameterConfiguration|null
     */
    public function getConfig(): ?ParameterConfiguration
    {
        return $this->config;
    }

    public function __toString()
    {
        return "ExperimentEvaluation";
    }
}