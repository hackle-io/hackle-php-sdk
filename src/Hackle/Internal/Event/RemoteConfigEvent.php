<?php

namespace Hackle\Internal\Event;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Model\RemoteConfigParameter;
use Hackle\Internal\User\HackleUser;

final class RemoteConfigEvent extends UserEvent
{
    private $parameter;
    private $valueId;
    private $decisionReason;
    private $properties;

    /**
     * @param string $insertId
     * @param int $timestamp
     * @param HackleUser $user
     * @param RemoteConfigParameter $parameter
     * @param int|null $valueId
     * @param DecisionReason $decisionReason
     * @param array<string, object> $properties
     */
    public function __construct(
        string $insertId,
        int $timestamp,
        HackleUser $user,
        RemoteConfigParameter $parameter,
        ?int $valueId,
        DecisionReason $decisionReason,
        array $properties
    ) {
        parent::__construct($insertId, $timestamp, $user);
        $this->parameter = $parameter;
        $this->valueId = $valueId;
        $this->decisionReason = $decisionReason;
        $this->properties = $properties;
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
     * @return DecisionReason
     */
    public function getDecisionReason(): DecisionReason
    {
        return $this->decisionReason;
    }

    /**
     * @return object[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
