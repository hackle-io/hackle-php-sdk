<?php

namespace Hackle\Internal\Evaluation\Evaluator\RemoteConfig;

use Hackle\Common\DecisionReason;
use Hackle\Common\PropertiesBuilder;
use Hackle\Internal\Evaluation\Evaluator\ContextualEvaluator;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Target\RemoteConfigTargetRuleDeterminer;
use Hackle\Internal\Model\RemoteConfigParameterValue;
use Hackle\Internal\Model\ValueType;

/**
 * @template T
 * @template-extends ContextualEvaluator<RemoteConfigRequest<T>, RemoteConfigEvaluation<T>>
 */
final class RemoteConfigEvaluator extends ContextualEvaluator
{

    private $targetRuleDeterminer;

    public function __construct(RemoteConfigTargetRuleDeterminer $targetRuleDeterminer)
    {
        $this->targetRuleDeterminer = $targetRuleDeterminer;
    }

    function supports(EvaluatorRequest $request): bool
    {
        return $request instanceof RemoteConfigRequest;
    }

    protected function evaluateInternal($request, EvaluatorContext $context)
    {
        $propertiesBuilder = new PropertiesBuilder();
        $propertiesBuilder->add("requestValueType", $request->getRequiredType()->getValue());
        $propertiesBuilder->add("requestDefaultValue", $request->getDefaultValue());

        if (!array_key_exists($request->getParameter()->getIdentifierType(), $request->getUser()->getIdentifiers())) {
            return RemoteConfigEvaluation::ofDefault(
                $request,
                $context,
                DecisionReason::IDENTIFIER_NOT_FOUND(),
                $propertiesBuilder
            );
        }

        $targetRule = $this->targetRuleDeterminer->determineTargetRuleOrNull($request, $context);
        if ($targetRule !== null) {
            $propertiesBuilder->add("targetRuleKey", $targetRule->getKey());
            $propertiesBuilder->add("targetRuleName", $targetRule->getName());
            return $this->evaluation(
                $request,
                $context,
                $targetRule->getValue(),
                DecisionReason::TARGET_RULE_MATCH(),
                $propertiesBuilder
            );
        }
        return $this->evaluation(
            $request,
            $context,
            $request->getParameter()->getDefaultValue(),
            DecisionReason::DEFAULT_RULE(),
            $propertiesBuilder
        );
    }

    /**
     * @template T
     *
     * @param RemoteConfigRequest<T> $request
     * @param EvaluatorContext $context
     * @param RemoteConfigParameterValue $parameterValue
     * @param DecisionReason $reason
     * @param PropertiesBuilder $propertiesBuilder
     * @return RemoteConfigEvaluation<T>
     */
    private function evaluation(
        RemoteConfigRequest $request,
        EvaluatorContext $context,
        RemoteConfigParameterValue $parameterValue,
        DecisionReason $reason,
        PropertiesBuilder $propertiesBuilder
    ): RemoteConfigEvaluation {
        $isRequiredType = $this->isRequiredType($parameterValue->getRawValue(), $request->getRequiredType());

        if ($isRequiredType) {
            return RemoteConfigEvaluation::of(
                $request,
                $context,
                $parameterValue->getId(),
                $parameterValue->getRawValue(),
                $reason,
                $propertiesBuilder
            );
        } else {
            return RemoteConfigEvaluation::ofDefault(
                $request,
                $context,
                DecisionReason::TYPE_MISMATCH(),
                $propertiesBuilder
            );
        }
    }

    /**
     * @param mixed $value
     * @param ValueType $requiredType
     * @return bool
     */
    private function isRequiredType($value, ValueType $requiredType): bool
    {
        switch ($requiredType) {
            case ValueType::STRING:
                return is_string($value);
            case ValueType::NUMBER:
                return is_numeric($value);
            case ValueType::BOOLEAN:
                return is_bool($value);
            default:
                return false;
        }
    }
}