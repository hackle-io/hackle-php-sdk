<?php

namespace Hackle\Internal\Model;

class Condition
{
    /** @var Key */
    private $_key;

    /** @var Match */
    private $_match;

    /**
     * @param Key $_key
     * @param Match $_match
     */
    public function __construct(Key $_key, Match $_match)
    {
        $this->_key = $_key;
        $this->_match = $_match;
    }

    /**
     * @return Key
     */
    public function getKey(): Key
    {
        return $this->_key;
    }

    /**
     * @return Match
     */
    public function getMatch(): Match
    {
        return $this->_match;
    }
}
