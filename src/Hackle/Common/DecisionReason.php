<?php

namespace Hackle\Common;

use Hackle\Internal\Lang\Enum;

/**
 * @method static SDK_NOT_READY()
 * @method static EXCEPTION()
 * @method static INVALID_INPUT()
 * @method static EXPERIMENT_NOT_FOUND()
 * @method static EXPERIMENT_DRAFT()
 * @method static EXPERIMENT_PAUSED()
 * @method static EXPERIMENT_COMPLETED()
 * @method static OVERRIDDEN()
 * @method static TRAFFIC_NOT_ALLOCATED()
 * @method static NOT_IN_MUTUAL_EXCLUSION_EXPERIMENT()
 * @method static IDENTIFIER_NOT_FOUND()
 * @method static VARIATION_DROPPED()
 * @method static TRAFFIC_ALLOCATED()
 * @method static TRAFFIC_ALLOCATED_BY_TARGETING()
 * @method static NOT_IN_EXPERIMENT_TARGET()
 * @method static FEATURE_FLAG_NOT_FOUND()
 * @method static FEATURE_FLAG_INACTIVE()
 * @method static INDIVIDUAL_TARGET_MATCH()
 * @method static TARGET_RULE_MATCH()
 * @method static DEFAULT_RULE()
 * @method static REMOTE_CONFIG_PARAMETER_NOT_FOUND()
 * @method static TYPE_MISMATCH()
 */
class DecisionReason extends Enum
{
    public const SDK_NOT_READY = "SDK_NOT_READY";
    public const EXCEPTION = "EXCEPTION";
    public const INVALID_INPUT = "INVALID_INPUT";
    public const EXPERIMENT_NOT_FOUND = "EXPERIMENT_NOT_FOUND";
    public const EXPERIMENT_DRAFT = "EXPERIMENT_DRAFT";
    public const EXPERIMENT_PAUSED = "EXPERIMENT_PAUSED";
    public const EXPERIMENT_COMPLETED = "EXPERIMENT_COMPLETED";
    public const OVERRIDDEN = "OVERRIDDEN";
    public const TRAFFIC_NOT_ALLOCATED = "TRAFFIC_NOT_ALLOCATED";
    public const NOT_IN_MUTUAL_EXCLUSION_EXPERIMENT = "NOT_IN_MUTUAL_EXCLUSION_EXPERIMENT";
    public const IDENTIFIER_NOT_FOUND = "IDENTIFIER_NOT_FOUND";
    public const VARIATION_DROPPED = "VARIATION_DROPPED";
    public const TRAFFIC_ALLOCATED = "TRAFFIC_ALLOCATED";
    public const TRAFFIC_ALLOCATED_BY_TARGETING = "TRAFFIC_ALLOCATED_BY_TARGETING";
    public const NOT_IN_EXPERIMENT_TARGET = "NOT_IN_EXPERIMENT_TARGET";
    public const FEATURE_FLAG_NOT_FOUND = "FEATURE_FLAG_NOT_FOUND";
    public const FEATURE_FLAG_INACTIVE = "FEATURE_FLAG_INACTIVE";
    public const INDIVIDUAL_TARGET_MATCH = "INDIVIDUAL_TARGET_MATCH";
    public const TARGET_RULE_MATCH = "TARGET_RULE_MATCH";
    public const DEFAULT_RULE = "DEFAULT_RULE";
    public const REMOTE_CONFIG_PARAMETER_NOT_FOUND = "REMOTE_CONFIG_PARAMETER_NOT_FOUND";
    public const TYPE_MISMATCH = "TYPE_MISMATCH";
}
