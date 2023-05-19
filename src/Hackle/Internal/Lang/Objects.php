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

/**
 * https://www.uuidgenerator.net/dev-corner/php
 */
function guidv4($data = null): string
{
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}