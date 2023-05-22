<?php

namespace Hackle\Internal\Utils;

use Psr\Log\InvalidArgumentException;

class Arrays
{
    public static function mapNotNull(array $array, callable $transform): array
    {
        $result = [];
        if ($array === null) {
            throw new InvalidArgumentException("array is null");
        }
        if ($transform === null) {
            throw new InvalidArgumentException("transform is null");
        }
        return array_map(
            $transform,
            array_filter($array, static function ($item) {
                return !is_null($item);
            })
        );
    }

    public static function associate(array $array, callable $keyMapper, callable $valueMapper): array
    {
        if ($array === null) {
            throw new InvalidArgumentException("array is null");
        }
        if ($keyMapper === null) {
            throw new InvalidArgumentException("keyMapper is null");
        }
        if ($valueMapper === null) {
            throw new InvalidArgumentException("valueMapper is null");
        }
        $result = [];
        foreach ($array as $item) {
            $key = $keyMapper($item);
            $value = $valueMapper($item);
            $result[$key] = $value;
        }
        return $result;
    }

    public static function associateBy(array $array, callable $keyMapper): array
    {
        if ($array === null) {
            throw new InvalidArgumentException("array is null");
        }
        if ($keyMapper === null) {
            throw new InvalidArgumentException("keyMapper is null");
        }
        $result = [];
        foreach ($array as $item) {
            $key = $keyMapper($item);
            $result[$key] = $item;
        }
        return $result;
    }
}
