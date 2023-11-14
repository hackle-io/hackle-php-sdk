<?php

namespace Hackle\Tests\Internal\Repository;

use Hackle\Internal\Repository\Repository;

class MemoryRepository implements Repository
{
    private $data = array();

    public function get(string $key): ?string
    {
        return $this->data[$key] ?? null;
    }

    public function set(string $key, ?string $value)
    {
        $this->data[$key] = $value;
    }
}
