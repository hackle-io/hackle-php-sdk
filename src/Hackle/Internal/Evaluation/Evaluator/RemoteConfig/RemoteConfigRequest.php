<?php

namespace Hackle\Internal\Evaluation\Evaluator\RemoteConfig;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorKey;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorType;
use Hackle\Internal\Model\RemoteConfigParameter;
use Hackle\Internal\Model\ValueType;
use Hackle\Internal\User\HackleUser;
use Hackle\Internal\Workspace\Workspace;

/**
 * @template T
 */
class RemoteConfigRequest implements EvaluatorRequest
{
    private $key;
    private $workspace;
    private $user;
    private $parameter;
    private $requiredType;
    private $defaultValue;

    /**
     * @param Workspace $workspace
     * @param HackleUser $user
     * @param RemoteConfigParameter $parameter
     * @param ValueType $requiredType
     * @param T $defaultValue
     */
    public function __construct(
        Workspace $workspace,
        HackleUser $user,
        RemoteConfigParameter $parameter,
        ValueType $requiredType,
        $defaultValue
    ) {
        $this->key = new EvaluatorKey(EvaluatorType::REMOTE_CONFIG(), $parameter->getId());
        $this->workspace = $workspace;
        $this->user = $user;
        $this->parameter = $parameter;
        $this->requiredType = $requiredType;
        $this->defaultValue = $defaultValue;
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
     * @return RemoteConfigParameter
     */
    public function getParameter(): RemoteConfigParameter
    {
        return $this->parameter;
    }

    /**
     * @return ValueType
     */
    public function getRequiredType(): ValueType
    {
        return $this->requiredType;
    }

    /**
     * @return T
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function __toString()
    {
        return "EvaluatorRequest(type=REMOTE_CONFIG, key={$this->parameter->getKey()})";
    }
}
