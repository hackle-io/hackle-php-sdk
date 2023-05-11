<?php

namespace Hackle\Common;

interface Config
{
    public function getString(string $key, string $defaultValue): string;

    public function getInt(string $key, int $defaultValue): int;

    public function getFloat(string $key, float $defaultValue): float;

    public function getBool(string $key, bool $defaultValue): bool;
}
