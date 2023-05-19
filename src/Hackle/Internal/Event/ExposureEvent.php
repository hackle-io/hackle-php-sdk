<?php

namespace Hackle\Internal\Event;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\User\HackleUser;

final class ExposureEvent extends UserEvent
{

    private $experiment;
    private $variationId;
    private $variationKey;
    private $decisionReason;
    private $properties;

    /**
     * @param string $insertId
     * @param int $timestamp
     * @param HackleUser $user
     * @param Experiment $experiment
     * @param int|null $variationId
     * @param string $variationKey
     * @param DecisionReason $decisionReason
     * @param array<string, object> $properties
     */
    public function __construct(
        string $insertId,
        int $timestamp,
        HackleUser $user,
        Experiment $experiment,
        ?int $variationId,
        string $variationKey,
        DecisionReason $decisionReason,
        array $properties
    ) {
        parent::__construct($insertId, $timestamp, $user);
        $this->experiment = $experiment;
        $this->variationId = $variationId;
        $this->variationKey = $variationKey;
        $this->decisionReason = $decisionReason;
        $this->properties = $properties;
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
