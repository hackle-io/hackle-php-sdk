<?php

namespace Hackle\Internal\Model;

class Experiment
{
    private $_id;
    private $_key;
    private $_type;
    private $_identifierType;
    private $_status;
    private $_version;
    private $_variations;
    private $_userOverrides;
    private $_segmentOverrides;
    private $_targetAudiences;
    private $_targetRules;
    private $_defaultRule;
    private $_containerId;
    private $_winnerVariationId;

    protected function getVariationOrNull(int $variationId): ?Variation
    {
        $variations = array_filter($this->variations, function (Variation $variation) use ($variationId) {
            return $variation->getId() == $variationId;
        });
        if (empty($variations)) {
            return null;
        }
        return array_values($variations)[0];
    }
}
