<?php

namespace Hackle;

use Hackle\Common\HackleEvent;
use Hackle\Common\ExperimentDecision;
use Hackle\Common\FeatureFlagDecision;
use Hackle\Common\HackleUser;
use Hackle\Common\RemoteConfig;

interface HackleClient
{
    /**
     * Decide the variation to expose to the user for experiment.
     *
     * This method does not block the calling thread.
     *
     * @param int $experimentKey
     * @param HackleUser $user
     * @return string decided variation for the user, or [Variation::getControl()]
     */
    public function variation(int $experimentKey, HackleUser $user): string;

    /**
     * Decide the variation to expose to the user for experiment, and returns an object that
     * describes the way the variation was decided.
     *
     * @param int $experimentKey the unique key of the experiment.
     * @param HackleUser $user the identifier of user to participate in the experiment. MUST NOT be null.
     * @return ExperimentDecision object
     */
    public function variationDetail(int $experimentKey, HackleUser $user): ExperimentDecision;

    /**
     * Decide whether the feature is turned on to the user.
     *
     * @param int $featureKey the unique key for the feature.
     * @param HackleUser $user the user requesting the feature.
     * @return bool True if the feature is on.
     *              False if the feature is off.
     */
    public function isFeatureOn(int $featureKey, HackleUser $user): bool;

    /**
     * Decide whether the feature is turned on to the user, and returns an object that
     * describes the way the flag was decided.
     *
     * @param int $featureKey the unique key for the feature.
     * @param HackleUser $user the user requesting the feature.
     * @return FeatureFlagDecision object
     */
    public function featureFlagDetail(int $featureKey, HackleUser $user): FeatureFlagDecision;

    /**
     * Records the event that occurred by the user.
     *
     * This method does not block the calling thread.
     *
     * @param HackleEvent $event the event that occurred. MUST NOT be null.
     * @param HackleUser $user the user that occurred the event. MUST NOT be null.
     * @return void
     */
    public function track(HackleEvent $event, HackleUser $user): void;

    /**
     * Returns a instance of Hackle Remote Config.
     */
    public function remoteConfig(HackleUser $user): RemoteConfig;
}
