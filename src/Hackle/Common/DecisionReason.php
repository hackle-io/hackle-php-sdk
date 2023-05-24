<?php

namespace Hackle\Common;

use Hackle\Internal\Lang\Enum;

/**
 * @method static EXCEPTION()
 * @method static INVALID_INPUT()
 * @method static IDENTIFIER_NOT_FOUND()
 * @method static TYPE_MISMATCH()
 * @method static TARGET_RULE_MATCH()
 * @method static DEFAULT_RULE()
 * @method static SDK_NOT_READY()
 * @method static EXPERIMENT_NOT_FOUND()
 * @method static FEATURE_FLAG_NOT_FOUND()
 * @method static REMOTE_CONFIG_PARAMETER_NOT_FOUND()
 * @method static EXPERIMENT_COMPLETED()
 * @method static NOT_IN_MUTUAL_EXCLUSION_EXPERIMENT()
 * @method static NOT_IN_EXPERIMENT_TARGET()
 * @method static TRAFFIC_NOT_ALLOCATED()
 * @method static VARIATION_DROPPED()
 * @method static TRAFFIC_ALLOCATED()
 * @method static EXPERIMENT_DRAFT()
 * @method static OVERRIDDEN()
 * @method static INDIVIDUAL_TARGET_MATCH()
 * @method static EXPERIMENT_PAUSED()
 * @method static FEATURE_FLAG_INACTIVE()
 */
class DecisionReason extends Enum
{

    /**
     * Indicates that the sdk is not ready to use. e.g. invalid SDK key.
     */
    const SDK_NOT_READY = "SDK_NOT_READY";

    /**
     * Indicates that the variation could not be decided due to an unexpected exception.
     */
    const EXCEPTION = "EXCEPTION";

    /**
     * Indicates that the input value is invalid.
     */
    const INVALID_INPUT = "INVALID_INPUT";

    /**
     * Indicates that no experiment was found for the experiment key provided by the caller.
     */
    const EXPERIMENT_NOT_FOUND = "EXPERIMENT_NOT_FOUND";

    /**
     * Indicates that the experiment is in draft.
     */
    const EXPERIMENT_DRAFT = "EXPERIMENT_DRAFT";

    /**
     * Indicates that the experiment was paused.
     */
    const EXPERIMENT_PAUSED = "EXPERIMENT_PAUSED";

    /**
     * Indicates that the experiment was completed.
     */
    const EXPERIMENT_COMPLETED = "EXPERIMENT_COMPLETED";

    /**
     * Indicates that the user has been overridden as a specific variation.
     */
    const OVERRIDDEN = "OVERRIDDEN";

    /**
     * Indicates that the experiment is running but the user is not allocated to the experiment.
     */
    const TRAFFIC_NOT_ALLOCATED = "TRAFFIC_NOT_ALLOCATED";

    /**
     * Indicates that the experiment is running but the user is not allocated to the mutual exclusion experiment.
     */
    const NOT_IN_MUTUAL_EXCLUSION_EXPERIMENT = "NOT_IN_MUTUAL_EXCLUSION_EXPERIMENT";

    /**
     * Indicates that no found identifier of experiment for the user provided by the caller.
     */
    const IDENTIFIER_NOT_FOUND = "IDENTIFIER_NOT_FOUND";

    /**
     * Indicates that the original decided variation has been dropped.
     */
    const VARIATION_DROPPED = "VARIATION_DROPPED";

    /**
     * Indicates that the user has been allocated to the experiment.
     */
    const TRAFFIC_ALLOCATED = "TRAFFIC_ALLOCATED";

    /**
     * Indicates that traffic was allocated by targeting from another experiment.
     */
    const TRAFFIC_ALLOCATED_BY_TARGETING = "TRAFFIC_ALLOCATED_BY_TARGETING";

    /**
     * Indicates that the user is not the target of the experiment.
     */
    const NOT_IN_EXPERIMENT_TARGET = "NOT_IN_EXPERIMENT_TARGET";

    /**
     * Indicates that no feature flag was found for the feature key provided by the caller.
     */
    const FEATURE_FLAG_NOT_FOUND = "FEATURE_FLAG_NOT_FOUND";

    /**
     * Indicates that the feature flag is inactive.
     */
    const FEATURE_FLAG_INACTIVE = "FEATURE_FLAG_INACTIVE";

    /**
     * Indicates that the user is matched to the individual target.
     */
    const INDIVIDUAL_TARGET_MATCH = "INDIVIDUAL_TARGET_MATCH";

    /**
     * Indicates that the user is matched to the target rule.
     */
    const TARGET_RULE_MATCH = "TARGET_RULE_MATCH";

    /**
     * Indicates that the user did not match any individual targets or target rules.
     */
    const DEFAULT_RULE = "DEFAULT_RULE";

    /**
     * Indicates that no remote config parameter was found for the parameter key provided by the caller.
     */
    const REMOTE_CONFIG_PARAMETER_NOT_FOUND = "REMOTE_CONFIG_PARAMETER_NOT_FOUND";

    /**
     * Indicates a mismatch between result type and request type.
     */
    const TYPE_MISMATCH = "TYPE_MISMATCH";
}
