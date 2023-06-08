<?php

namespace Hackle\Internal\Model;

class CoreVersion
{
    /** @var int */
    private $major;

    /** @var int */
    private $minor;

    /** @var int */
    private $patch;

    /**
     * @param int $major
     * @param int $minor
     * @param int $patch
     */
    public function __construct(int $major, int $minor, int $patch)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
    }

    public function compareTo(CoreVersion $other): int
    {
        if ($this->major != $other->major) {
            return ($this->major < $other->major) ? -1 : 1;
        }
        if ($this->minor != $other->minor) {
            return ($this->minor < $other->minor) ? -1 : 1;
        }
        if ($this->patch != $other->patch) {
            return ($this->patch < $other->patch) ? -1 : 1;
        }
        return 0;
    }

    public function __toString(): string
    {
        return $this->major . "." . $this->minor . "." . $this->patch;
    }
}
