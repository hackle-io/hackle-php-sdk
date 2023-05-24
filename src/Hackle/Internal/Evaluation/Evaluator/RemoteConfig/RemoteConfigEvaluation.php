<?php

namespace Hackle\Internal\Evaluation\Evaluator\RemoteConfig;

use Hackle\Common\DecisionReason;
use Hackle\Common\PropertiesBuilder;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorEvaluation;
use Hackle\Internal\Model\RemoteConfigParameter;

/**
 * @template T
 */
class RemoteConfigEvaluation implements EvaluatorEvaluation
{
    private $reason;
    private $targetEvaluations;
    private $parameter;
    private $valueId;
    private $value;
    private $properties;

    /**
     * @param DecisionReason $reason
     * @param array $targetEvaluations
     * @param RemoteConfigParameter $parameter
     * @param int|null $valueId
     * @param T $value
     * @param array<string, object> $properties
     */
    public function __construct(
        DecisionReason $reason,
        array $targetEvaluations,
        RemoteConfigParameter $parameter,
        ?int $valueId,
        $value,
        array $properties
    ) {
        $this->reason = $reason;
        $this->targetEvaluations = $targetEvaluations;
        $this->parameter = $parameter;
        $this->valueId = $valueId;
        $this->value = $value;
        $this->properties = $properties;
    }

    /**
     * @template T
     *
     * @param RemoteConfigRequest<T> $request
     * @param EvaluatorContext $context
     * @param int|null $valueId
     * @param T $value
     * @param DecisionReason $reason
     * @param PropertiesBuilder $propertiesBuilder
     * @return RemoteConfigEvaluation<T>
     */
    public static function of(
        RemoteConfigRequest $request,
        EvaluatorContext $context,
        ?int $valueId,
        $value,
        DecisionReason $reason,
        PropertiesBuilder $propertiesBuilder
    ): RemoteConfigEvaluation {
        $propertiesBuilder->add("returnValue", $value);
        return new RemoteConfigEvaluation(
            $reason,
            $context->getTargetEvaluations(),
            $request->getParameter(),
            $valueId,
            $value,
            $propertiesBuilder->build()
        );
    }

    /**
     * @template T
     *
     * @param RemoteConfigRequest<T> $request
     * @param EvaluatorContext $context
     * @param DecisionReason $reason
     * @param PropertiesBuilder $propertiesBuilder
     * @return RemoteConfigEvaluation<T>
     */
    public static function ofDefault(
        RemoteConfigRequest $request,
        EvaluatorContext $context,
        DecisionReason $reason,
        PropertiesBuilder $propertiesBuilder
    ): RemoteConfigEvaluation {
        return RemoteConfigEvaluation::of(
            $request,
            $context,
            null,
            $request->getDefaultValue(),
            $reason,
            $propertiesBuilder
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
     * @return array
     */
    public function getTargetEvaluations(): array
    {
        return $this->targetEvaluations;
    }

    /**
     * @return RemoteConfigParameter
     */
    public function getParameter(): RemoteConfigParameter
    {
        return $this->parameter;
    }

    /**
     * @return int|null
     */
    public function getValueId(): ?int
    {
        return $this->valueId;
    }

    /**
     * @return T
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array<string, object>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
