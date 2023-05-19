<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Model\Enums\SegmentType;

class Segment
{
    /**@var int */
    private $_id;

    /**@var string */
    private $_key;

    /** @var SegmentType */
    private $_type;

    /**@var array */
    private $_targets;

    /**
     * @param int $_id
     * @param string $_key
     * @param SegmentType $_type
     * @param array $_targets
     */
    public function __construct(int $_id, string $_key, SegmentType $_type, array $_targets)
    {
        $this->_id = $_id;
        $this->_key = $_key;
        $this->_type = $_type;
        $this->_targets = $_targets;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->_key;
    }

    /**
     * @return SegmentType
     */
    public function getType(): SegmentType
    {
        return $this->_type;
    }

    /**
     * @return array
     */
    public function getTargets(): array
    {
        return $this->_targets;
    }
}
