<?php

namespace Hackle\Internal\Lang;

use Hackle\Internal\Model\Version;

class Objects
{

    public static function asStringOrNull($value): ?string
    {
        return null;
    }

    public static function asFloatOrNull($value): ?float
    {
        return null;
    }

    public static function asBoolOrNull($value): ?bool
    {
        return null;
    }

    public static function asVersionOrNull($value): ?Version
    {
        return null;
    }

    public static function asIntOrNull($value): ?int
    {
        return null;
    }

}


function required(bool $expression, string $message)
{
    if (!$expression) {
        throw new \InvalidArgumentException($message);
    }
}

/**
 * @template T
 * @param ?T $value
 * @param string $message
 * @return T
 */
function requireNotNull($value, string $message)
{
    if ($value === null) {
        throw new \InvalidArgumentException($message);
    }
    return $value;
}