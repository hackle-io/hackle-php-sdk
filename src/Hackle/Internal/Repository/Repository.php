<?php

namespace Hackle\Internal\Repository;

interface Repository
{
    public function get(string $key): ?string;

    public function set(string $key, ?string $value);
}
