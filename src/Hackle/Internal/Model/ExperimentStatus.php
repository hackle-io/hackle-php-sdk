<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Lang\Enum;

/**
 * @method static DRAFT()
 * @method static RUNNING()
 * @method static PAUSED()
 * @method static COMPLETED()
 */
class ExperimentStatus extends Enum
{
    public const DRAFT = "DRAFT";
    public const RUNNING = "RUNNING";
    public const PAUSED = "PAUSED";
    public const COMPLETED = "COMPLETED";

    private const STATUSES = array(
        "READY" => self::DRAFT,
        "RUNNING" => self::RUNNING,
        "PAUSED" => self::PAUSED,
        "STOPPED" => self::COMPLETED
    );

    /**
     * @param string $code
     * @return self|null
     */
    public static function fromExecutionStatusOrNull(string $code): ?self
    {
        if (!isset(ExperimentStatus::STATUSES[$code])) {
            return null;
        }
        return ExperimentStatus::fromOrNull(ExperimentStatus::STATUSES[$code]);
    }
}
