<?php

namespace Hackle\Internal\Client;

use Hackle\Common\RemoteConfig;

class HackleRemoteConfigImpl implements RemoteConfig
{
    public function getString(string $key, string $defaultValue): string
    {
        return "";
    }

    public function getInt(string $key, int $defaultValue): int
    {
        return 0;
    }

    public function getFloat(string $key, float $defaultValue): float
    {
        return 0;
    }

    public function getBool(string $key, bool $defaultValue): bool
    {
        return false;
    }
}
