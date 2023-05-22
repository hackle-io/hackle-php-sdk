<?php

namespace Hackle\Internal\Event;

use Hackle\Common\PropertiesBuilder;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorEvaluation;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluation;
use Hackle\Internal\Time\Clock;

final class UserEventFactory
{
    private $clock;


    private const  ROOT_TYPE = "\$targetingRootType";
    private const  ROOT_ID = "\$targetingRootId";
    private const CONFIG_ID_PROPERTY_KEY = "\$parameterConfigurationId";

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    /**
     * @param EvaluatorRequest $request
     * @param EvaluatorEvaluation $evaluation
     * @return array<UserEvent>
     */
    public function create(EvaluatorRequest $request, EvaluatorEvaluation $evaluation): array
    {
        $timestamp = $this->clock->currentMillis();
        $events = [];

        $rootEvent = $this->createEvent($request, $evaluation, $timestamp, new PropertiesBuilder());
        $events[] = $rootEvent;

        foreach ($evaluation->getTargetEvaluations() as $targetEvaluation) {
            $propertiesBuilder = new PropertiesBuilder();
            $propertiesBuilder->add(UserEventFactory::ROOT_TYPE, $request->getKey()->getType()->getValue());
            $propertiesBuilder->add(UserEventFactory::ROOT_ID, $request->getKey()->getId());
            $targetEvent = $this->createEvent($request, $targetEvaluation, $timestamp, $propertiesBuilder);
            $events[] = $targetEvent;
        }

        return $events;
    }

    private function createEvent(
        EvaluatorRequest $request,
        EvaluatorEvaluation $evaluation,
        int $timestamp,
        PropertiesBuilder $propertiesBuilder
    ): UserEvent {
        if ($evaluation instanceof ExperimentEvaluation) {
            if ($evaluation->getConfig() !== null) {
                $propertiesBuilder->add(UserEventFactory::CONFIG_ID_PROPERTY_KEY, $evaluation->getConfig()->getId());
            }
            return UserEvent::exposure($request->getUser(), $evaluation, $propertiesBuilder->build(), $timestamp);
        }

        if ($evaluation instanceof RemoteConfigEvaluation) {
            $propertiesBuilder->addAll($evaluation->getProperties());
            return UserEvent::remoteConfig($request->getUser(), $evaluation, $propertiesBuilder->build(), $timestamp);
        }

        throw new \InvalidArgumentException("Unsupported Evaluation [$evaluation]");
    }
}