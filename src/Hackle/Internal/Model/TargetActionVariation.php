<?php

namespace Hackle\Internal\Model;

class TargetActionVariation extends TargetAction
{
    private $variationId;

    public function __construct(int $variationId)
    {
        $this->variationId = $variationId;
    }

    /**
     * @return int
     */
    public function getVariationId(): int
    {
        return $this->variationId;
    }
}
