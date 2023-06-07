<?php

namespace Hackle\Internal\Core;

use Hackle\Common\DecisionReason;
use Hackle\Common\Event;
use Hackle\Common\ExperimentDecision;
use Hackle\Common\FeatureFlagDecision;
use Hackle\Common\RemoteConfigDecision;
use Hackle\Internal\Evaluation\Evaluator\DelegatingEvaluator;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluator;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigEvaluator;
use Hackle\Internal\Evaluation\Evaluator\RemoteConfig\RemoteConfigRequest;
use Hackle\Internal\Evaluation\Flow\EvaluationFlowFactory;
use Hackle\Internal\Event\Processor\UserEventProcessor;
use Hackle\Internal\Event\UserEvent;
use Hackle\Internal\Event\UserEventFactory;
use Hackle\Internal\Model\EventType;
use Hackle\Internal\Model\ValueType;
use Hackle\Internal\Time\Clock;
use Hackle\Internal\Time\SystemClock;
use Hackle\Internal\User\InternalHackleUser;
use Hackle\Internal\Workspace\WorkspaceFetcher;

class HackleCore
{
    private $experimentEvaluator;
    private $remoteConfigEvaluator;
    private $workspaceFetcher;
    private $eventFactory;
    private $eventProcessor;
    private $clock;

    public function __construct(
        ExperimentEvaluator $experimentEvaluator,
        RemoteConfigEvaluator $remoteConfigEvaluator,
        WorkspaceFetcher $workspaceFetcher,
        UserEventFactory $eventFactory,
        UserEventProcessor $eventProcessor,
        Clock $clock
    ) {
        $this->experimentEvaluator = $experimentEvaluator;
        $this->remoteConfigEvaluator = $remoteConfigEvaluator;
        $this->workspaceFetcher = $workspaceFetcher;
        $this->eventFactory = $eventFactory;
        $this->eventProcessor = $eventProcessor;
        $this->clock = $clock;
    }

    public static function create(WorkspaceFetcher $workspaceFetcher, UserEventProcessor $eventProcessor): HackleCore
    {
        $delegatingEvaluator = new DelegatingEvaluator();
        $evaluationFlowFactory = new EvaluationFlowFactory($delegatingEvaluator);

        $experimentEvaluator = new ExperimentEvaluator($evaluationFlowFactory);
        $remoteConfigEvaluator = new RemoteConfigEvaluator(
            $evaluationFlowFactory->getRemoteConfigTargetRuleDeterminer()
        );

        $delegatingEvaluator->add($experimentEvaluator);
        $delegatingEvaluator->add($remoteConfigEvaluator);

        $clock = new SystemClock();
        return new HackleCore(
            $experimentEvaluator,
            $remoteConfigEvaluator,
            $workspaceFetcher,
            new UserEventFactory($clock),
            $eventProcessor,
            $clock
        );
    }

    public function experiment(int $experimentKey, InternalHackleUser $user, string $defaultVariationKey): ExperimentDecision
    {
        $workspace = $this->workspaceFetcher->fetch();
        if ($workspace === null) {
            return ExperimentDecision::of($defaultVariationKey, DecisionReason::SDK_NOT_READY());
        }

        $experiment = $workspace->getExperimentOrNull($experimentKey);
        if ($experiment === null) {
            return ExperimentDecision::of($defaultVariationKey, DecisionReason::EXPERIMENT_NOT_FOUND());
        }

        $request = ExperimentRequest::of($workspace, $user, $experiment, $defaultVariationKey);
        $evaluation = $this->experimentEvaluator->evaluate($request, new EvaluatorContext());

        $events = $this->eventFactory->create($request, $evaluation);
        foreach ($events as $event) {
            $this->eventProcessor->process($event);
        }

        return ExperimentDecision::of(
            $evaluation->getVariationKey(),
            $evaluation->getReason(),
            $evaluation->getConfig()
        );
    }

    public function featureFlag(int $featureKey, InternalHackleUser $user): FeatureFlagDecision
    {
        $workspace = $this->workspaceFetcher->fetch();
        if ($workspace === null) {
            return FeatureFlagDecision::off(DecisionReason::SDK_NOT_READY());
        }

        $featureFlag = $workspace->getFeatureFlagOrNull($featureKey);
        if ($featureFlag === null) {
            return FeatureFlagDecision::off(DecisionReason::FEATURE_FLAG_NOT_FOUND());
        }

        $request = ExperimentRequest::of($workspace, $user, $featureFlag, "A");
        $evaluation = $this->experimentEvaluator->evaluate($request, new EvaluatorContext());

        $events = $this->eventFactory->create($request, $evaluation);
        foreach ($events as $event) {
            $this->eventProcessor->process($event);
        }

        if ($evaluation->getVariationKey() === "A") {
            return FeatureFlagDecision::off($evaluation->getReason(), $evaluation->getConfig());
        } else {
            return FeatureFlagDecision::on($evaluation->getReason(), $evaluation->getConfig());
        }
    }

    public function track(Event $event, InternalHackleUser $user)
    {
        $workspace = $this->workspaceFetcher->fetch();

        $eventType = null;
        if ($workspace !== null) {
            $eventType = $workspace->getEventTypeOrNull($event->getKey());
        }

        if ($eventType === null) {
            $eventType = new EventType(0, $event->getKey());
        }


        $trackEvent = UserEvent::track($user, $eventType, $event, $this->clock->currentMillis());
        $this->eventProcessor->process($trackEvent);
    }

    /**
     * @template T
     *
     * @param string $parameterKey
     * @param InternalHackleUser $user
     * @param ValueType $requiredType
     * @param T $defaultValue
     * @return RemoteConfigDecision<T>
     */
    public function remoteConfig(
        string             $parameterKey,
        InternalHackleUser $user,
        ValueType          $requiredType,
                           $defaultValue
    ): RemoteConfigDecision {
        $workspace = $this->workspaceFetcher->fetch();
        if ($workspace === null) {
            return RemoteConfigDecision::of($defaultValue, DecisionReason::SDK_NOT_READY());
        }

        $parameter = $workspace->getRemoteConfigParameterOrNull($parameterKey);
        if ($parameter === null) {
            return RemoteConfigDecision::of($defaultValue, DecisionReason::REMOTE_CONFIG_PARAMETER_NOT_FOUND());
        }

        $request = new RemoteConfigRequest($workspace, $user, $parameter, $requiredType, $defaultValue);
        $evaluation = $this->remoteConfigEvaluator->evaluate($request, new EvaluatorContext());

        $events = $this->eventFactory->create($request, $evaluation);
        foreach ($events as $event) {
            $this->eventProcessor->process($event);
        }

        return RemoteConfigDecision::of($evaluation->getValue(), $evaluation->getReason());
    }
}