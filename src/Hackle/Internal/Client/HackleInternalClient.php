<?php

namespace Hackle\Internal\Client;

use Hackle\Common\Decision;
use Hackle\Common\DecisionReason;
use Hackle\Common\EmptyParameterConfig;
use Hackle\Common\Event;
use Hackle\Common\FeatureFlagDecision;
use Hackle\Common\RemoteConfigDecision;
use Hackle\Common\Variation;
use Hackle\Internal\Model\Enums\ValueType;
use Hackle\Internal\User\HackleUser;

class HackleInternalClient
{
    private $_workspaceFetcher;

    public function experiment(int $experimentKey, HackleUser $user, Variation $defaultVariation): Decision
    {
        return Decision::of(new Variation(Variation::A), new DecisionReason(DecisionReason::DEFAULT_RULE), new EmptyParameterConfig());
    }

    public function featureFlag(int $featureKey, HackleUser $user): FeatureFlagDecision
    {
        return new FeatureFlagDecision();
    }

    public function track(Event $event, HackleUser $user)
    {
    }

    public function remoteConfig(string $parameterKey, HackleUser $user, ValueType $valueType, $defaultValue): RemoteConfigDecision
    {
        return new RemoteConfigDecision();
    }
}
