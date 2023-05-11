<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Model\Enums\SegmentType;

class Segment
{
    private $_id;

    private $_key;

    /** @var SegmentType */
    private $_type;

    private $_targets;

    public function __construct($_id, $_key, SegmentType $_type, $_targets)
    {
        $this->_id = $_id;
        $this->_key = $_key;
        $this->_type = $_type;
        $this->_targets = $_targets;
    }
}
