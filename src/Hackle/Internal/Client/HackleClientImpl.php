<?php

namespace Hackle\Internal\Client;

use Exception;
use Hackle\Common\Decision;
use Hackle\Common\DecisionReason;
use Hackle\Common\EmptyParameterConfig;
use Hackle\Common\Event;
use Hackle\Common\FeatureFlagDecision;
use Hackle\Common\RemoteConfig;
use Hackle\Common\User;
use Hackle\Common\Variation;
use Hackle\HackleClient;
use Hackle\Internal\User\HackleUserResolver;
use Psr\Log\LoggerInterface;

class HackleClientImpl implements HackleClient
{
    /** @var HackleInternalClient */
    private $_client;

    /** @var LoggerInterface */
    private $_logger;

    /** @var HackleUserResolver */
    private $_userResolver;

    public function variation(int $experimentKey, User $user): Variation
    {
        return $this->variationDetail($experimentKey, $user)->getVariation();
    }

    public function variationDetail(int $experimentKey, User $user): Decision
    {
        try {
            $hackleUser = $this->_userResolver->resolveOrNull($user);
            if ($hackleUser == null) {
                return Decision::of(Variation::getControl(), new DecisionReason(DecisionReason::INVALID_INPUT), new EmptyParameterConfig());
            } else {
                return $this->_client->experiment($experimentKey, $hackleUser, Variation::getControl());
            }
        } catch (Exception $e) {
            $this->_logger->error("Unexpected exception while deciding variation for experiment[$experimentKey]. Returning default variation[" . Variation::getControl() . "]: " . $e->getMessage());
            return Decision::of(Variation::getControl(), new DecisionReason(DecisionReason::EXCEPTION), new EmptyParameterConfig());
        }
    }

    public function isFeatureOn(int $featureKey, User $user): bool
    {
        return $this->featureFlagDetail($featureKey, $user)->isOn();
    }

    public function featureFlagDetail(int $featureKey, User $user): FeatureFlagDecision
    {
        try {
            $hackleUser = $this->_userResolver->resolveOrNull($user);
            if ($hackleUser == null) {
                return FeatureFlagDecision::off(new DecisionReason(DecisionReason::INVALID_INPUT), new EmptyParameterConfig());
            } else {
                return $this->_client->featureFlag($featureKey, $hackleUser);
            }
        } catch (Exception $e) {
            $this->_logger->error("Unexpected exception while deciding feature flag[$featureKey]. Returning default variation[Returning default flag[off]:" . $e->getMessage());
            return FeatureFlagDecision::off(new DecisionReason(DecisionReason::EXCEPTION), new EmptyParameterConfig());
        }
    }

    public function track(Event $event, User $user): void
    {
        try {
            $hackleUser = $this->_userResolver->resolveOrNull($user);
            if ($hackleUser == null) {
                return;
            } else {
                $this->_client->track($event, $hackleUser);
            }
        } catch (Exception $e) {
            $this->_logger->error("Unexpected exception while tracking event[" . $event->getKey() . "]:" . $e->getMessage());
        }
    }

    public function remoteConfig(User $user): RemoteConfig
    {
        return new HackleRemoteConfigImpl();
    }
}
