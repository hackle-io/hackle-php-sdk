<?php

namespace Hackle\Internal\Utils;

use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Lang\Pair;

class Arrays
{
    /**
     * @template T
     * @template R
     *
     * @param T[] $array
     * @param callable(T): ?R $transform
     * @return R[]
     */
    public static function mapNotNull(array $array, callable $transform): array
    {
        Objects::requireNotNull($array, "array");
        Objects::requireNotNull($transform, "transform");

        $result = [];
        foreach ($array as $item) {
            $mappedItem = $transform($item);
            if ($mappedItem != null) {
                $result[] = $mappedItem;
            }
        }
        return $result;
    }

    /**
     * @template T
     * @template K
     * @template V
     *
     * @param T[] $array
     * @param callable(T): Pair<K, V> $transform
     * @return array<K, V>
     */
    public static function associate(array $array, callable $transform): array
    {
        Objects::requireNotNull($array, "array");
        Objects::requireNotNull($transform, "transform");
        $result = [];
        foreach ($array as $item) {
            $entry = $transform($item);
            $result[$entry->getFirst()] = $entry->getSecond();
        }
        return $result;
    }

    /**
     * @template T
     * @template K
     *
     * @param T[] $array
     * @param callable(T): K $keySelector
     * @return array<K, T>
     */
    public static function associateBy(array $array, callable $keySelector): array
    {
        Objects::requireNotNull($array, "array");
        Objects::requireNotNull($keySelector, "keySelector");
        $result = [];
        foreach ($array as $item) {
            $result[$keySelector($item)] = $item;
        }
        return $result;
    }
}
