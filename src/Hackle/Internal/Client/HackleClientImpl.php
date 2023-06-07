<?php

namespace Hackle\Internal\Client;

use Exception;
use Hackle\Common\DecisionReason;
use Hackle\Common\EmptyParameterConfig;
use Hackle\Common\HackleEvent;
use Hackle\Common\ExperimentDecision;
use Hackle\Common\FeatureFlagDecision;
use Hackle\Common\RemoteConfig;
use Hackle\Common\HackleUser;
use Hackle\Common\Variation;
use Hackle\HackleClient;
use Hackle\Internal\Core\HackleCore;
use Hackle\Internal\User\InternalHackleUserResolver;
use Psr\Log\LoggerInterface;

class HackleClientImpl implements HackleClient
{
    /** @var HackleCore */
    private $core;

    /** @var InternalHackleUserResolver */
    private $userResolver;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param HackleCore $core
     * @param InternalHackleUserResolver $userResolver
     * @param LoggerInterface $logger
     */
    public function __construct(HackleCore $core, InternalHackleUserResolver $userResolver, LoggerInterface $logger)
    {
        $this->core = $core;
        $this->userResolver = $userResolver;
        $this->logger = $logger;
    }
    public function variation(int $experimentKey, HackleUser $user): string
    {
        return $this->variationDetail($experimentKey, $user)->getVariation();
    }

    public function variationDetail(int $experimentKey, HackleUser $user): ExperimentDecision
    {
        try {
            $hackleUser = $this->userResolver->resolveOrNull($user);
            if ($hackleUser === null) {
                return ExperimentDecision::of(Variation::getControl(), DecisionReason::INVALID_INPUT(), new EmptyParameterConfig());
            } else {
                return $this->core->experiment($experimentKey, $hackleUser, Variation::getControl());
            }
        } catch (Exception $e) {
            $this->logger->error("Unexpected exception while deciding variation for experiment[$experimentKey]. Returning default variation[" . Variation::getControl() . "]: " . $e->getMessage());
            return ExperimentDecision::of(Variation::getControl(), DecisionReason::EXCEPTION(), new EmptyParameterConfig());
        }
    }

    public function isFeatureOn(int $featureKey, HackleUser $user): bool
    {
        return $this->featureFlagDetail($featureKey, $user)->isOn();
    }

    public function featureFlagDetail(int $featureKey, HackleUser $user): FeatureFlagDecision
    {
        try {
            $hackleUser = $this->userResolver->resolveOrNull($user);
            if ($hackleUser === null) {
                return FeatureFlagDecision::off(new DecisionReason(DecisionReason::INVALID_INPUT), new EmptyParameterConfig());
            } else {
                return $this->core->featureFlag($featureKey, $hackleUser);
            }
        } catch (Exception $e) {
            $this->logger->error("Unexpected exception while deciding feature flag[$featureKey]. Returning default variation[Returning default flag[off]:" . $e->getMessage());
            return FeatureFlagDecision::off(new DecisionReason(DecisionReason::EXCEPTION), new EmptyParameterConfig());
        }
    }

    public function track(HackleEvent $event, HackleUser $user): void
    {
        try {
            $hackleUser = $this->userResolver->resolveOrNull($user);
            if ($hackleUser === null) {
                return;
            } else {
                $this->core->track($event, $hackleUser);
            }
        } catch (Exception $e) {
            $this->logger->error("Unexpected exception while tracking event[" . $event->getKey() . "]:" . $e->getMessage());
        }
    }

    public function remoteConfig(HackleUser $user): RemoteConfig
    {
        return new HackleRemoteConfigImpl($user, $this->core, $this->userResolver, $this->logger);
    }
}
