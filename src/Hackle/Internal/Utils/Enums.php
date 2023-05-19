<?php

namespace Hackle\Internal\Utils;

use ReflectionClass;
use ReflectionException;

class Enums
{
    public static function parseEnumOrNull(string $class, string $name)
    {
        try {
            $reflection = new ReflectionClass($class);
            $constants = $reflection->getConstants();
            if (!in_array($name, $constants)) {
                return null;
            }
            return $constants[$name];
        } catch (ReflectionException $e) {
            return null;
        }
    }
}
