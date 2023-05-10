<?php

namespace Hackle\Internal\Model;

class BucketAction extends Action
{
    private $_bucketId;

    public function __construct(int $_bucketId)
    {
        $this->_bucketId = $_bucketId;
    }

    public function getBucketId(): int
    {
        return $this->_bucketId;
    }
}
