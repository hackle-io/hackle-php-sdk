<?php

namespace Hackle\Internal\Model;

class Key
{

    /** @var KeyType */
    private $_type;

    private $_name;

    public function __construct(KeyType $_type, string $_name)
    {
        $this->_type = $_type;
        $this->_name = $_name;
    }

    /**
     * @return KeyType
     */
    public function getType(): KeyType
    {
        return $this->_type;
    }

    public function getName(): string
    {
        return $this->_name;
    }
}
