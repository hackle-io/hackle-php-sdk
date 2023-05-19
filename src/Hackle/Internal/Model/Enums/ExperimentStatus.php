<?php

namespace Hackle\Internal\Model\Enums;

use Hackle\Internal\Lang\Enum;
use ReflectionException;

class ExperimentStatus extends Enum
{
    const DRAFT = "DRAFT";

    const RUNNING = "RUNNING";

    const PAUSED = "PAUSED";

    const COMPLETED = "COMPLETED";

    private static $_status = array("READY" => self::DRAFT, "RUNNING" => self::RUNNING, "PAUSED" => self::PAUSED, "STOPPED" => self::COMPLETED);

    public static function fromExecutionStatusOrNull(string $code): ?self
    {
        try {
            $status = self::$_status[$code] ?? null;
            if ($status != null) {
                return new ExperimentStatus($status);
            }
            return null;
        } catch (ReflectionException $e) {
            return null;
        }
    }
}
