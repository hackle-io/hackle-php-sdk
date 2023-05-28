<?php

namespace Hackle\Internal\Lang;

use Hackle\Internal\Model\Version;

class Objects
{

    public static function asStringOrNull($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (string)$value;
        }

        return null;
    }

    public static function asFloatOrNull($value): ?float
    {
        return is_numeric($value) ? (float)$value : null;
    }

    public static function asBoolOrNull($value): ?bool
    {
        if ($value === null) {
            return null;
        }

        return is_bool($value) ? $value : null;
    }

    public static function asVersionOrNull($value): ?Version
    {
        return Version::parseOrNull($value);
    }

    public static function asIntOrNull($value): ?int
    {
        return is_numeric($value) ? intval($value) : null;
    }

    public static function require(bool $expression, string $message)
    {
        if (!$expression) {
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * @template T
     * @param T|null $value
     * @param string $message
     * @return T
     */
    public static function requireNotNull($value, string $message = "Required value is null.")
    {
        if ($value === null) {
            throw new \InvalidArgumentException($message);
        }
        return $value;
    }
}
