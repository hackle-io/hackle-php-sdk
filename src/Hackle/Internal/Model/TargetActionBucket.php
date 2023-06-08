<?php

namespace Hackle\Internal\Model;

class TargetActionBucket extends TargetAction
{
    private $bucketId;

    public function __construct(int $bucketId)
    {
        $this->bucketId = $bucketId;
    }

    /**
     * @return int
     */
    public function getBucketId(): int
    {
        return $this->bucketId;
    }
}
