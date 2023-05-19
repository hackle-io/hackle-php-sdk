<?php

namespace Hackle\Internal\Evaluation\Bucket;

use Hackle\Internal\Model\Bucket;
use Hackle\Internal\Model\Slot;

final class Bucketer
{
    private $murmur3Hash;

    public function __construct(Murmur3Hash $murmur3Hash)
    {
        $this->murmur3Hash = $murmur3Hash;
    }

    public function bucketing(Bucket $bucket, string $identifier): ?Slot
    {
        $slotNumber = $this->calculateSlotNumber($bucket->getSeed(), $bucket->getSlotSize(), $identifier);
        return $bucket->getSlotOrNull($slotNumber);
    }

    public function calculateSlotNumber(int $seed, int $slotSize, string $value): int
    {
        $hashValue = $this->murmur3Hash->hash($value, $seed);
        return abs($hashValue) % $slotSize;
    }
}