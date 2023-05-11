<?php

namespace Hackle\Internal\Model;

class CoreVersion
{
    private $_major;
    private $_minor;
    private $_patch;

    public function __construct(int $_major, int $_minor, int $_patch)
    {
        $this->_major = $_major;
        $this->_minor = $_minor;
        $this->_patch = $_patch;
    }

    public function compareTo(CoreVersion $other): int
    {
        if ($this->_major != $other->_major) {
            return ($this->_major < $other->_major) ? -1 : 1;
        }
        if ($this->_minor != $other->_minor) {
            return ($this->_minor < $other->_minor) ? -1 : 1;
        }
        if ($this->_patch != $other->_patch) {
            return ($this->_patch < $other->_patch) ? -1 : 1;
        }
        return 0;
    }

    public function __toString(): string
    {
        return $this->_major . "." . $this->_minor . "." . $this->_patch;
    }
}
