<?php

namespace Hackle\Internal\Event;

use Hackle\Common\Event;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluation;
use Hackle\Internal\Lang\Uuid;
use Hackle\Internal\Model\EventType;
use Hackle\Internal\User\HackleUser;


abstract class UserEvent
{
    private $insertId;
    private $timestamp;
    private $user;

    protected function __construct(string $insertId, int $timestamp, HackleUser $user)
    {
        $this->insertId = $insertId;
        $this->timestamp = $timestamp;
        $this->user = $user;
    }

    public static function exposure(
        HackleUser $user,
        ExperimentEvaluation $evaluation,
        array $properties,
        int $timestamp
    ): ExposureEvent {
        return new ExposureEvent(
            Uuid::guidv4(),
            $timestamp,
            $user,
            $evaluation->getExperiment(),
            $evaluation->getVariationId(),
            $evaluation->getVariationKey(),
            $evaluation->getReason(),
            $properties
        );
    }

    public static function track(HackleUser $user, EventType $eventType, Event $event, int $timestamp): TrackEvent
    {
        return new TrackEvent(Uuid::guidv4(), $timestamp, $user, $eventType, $event);
    }

    public static function remoteConfig(
        HackleUser $user,
        RemoteConfigEvaluation $evaluation,
        array $properties,
        int $timestamp
    ): RemoteConfigEvent {
        return new RemoteConfigEvent(
            Uuid::guidv4(),
            $timestamp,
            $user,
            $evaluation->getParameter(),
            $evaluation->getValueId(),
            $evaluation->getReason(),
            $properties
        );
    }

    /**
     * @return string
     */
    public function getInsertId(): string
    {
        return $this->insertId;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return HackleUser
     */
    public function getUser(): HackleUser
    {
        return $this->user;
    }
}
