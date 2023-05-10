<?php

namespace Hackle\Internal\Model;

class VariationAction extends Action
{
    private $_variationId;

    public function __construct(int $_variationId)
    {
        $this->_variationId = $_variationId;
    }

    public function getVariationId(): int
    {
        return $this->_variationId;
    }
}
