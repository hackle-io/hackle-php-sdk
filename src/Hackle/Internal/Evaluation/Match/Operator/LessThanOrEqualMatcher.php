<?php

namespace Hackle\Internal\Evaluation\Match\Operator;

use Hackle\Internal\Model\Version;

class LessThanOrEqualMatcher implements OperatorMatcher
{
    public function stringMatches(string $userValue, string $matchValue): bool
    {
        return $userValue <= $matchValue;
    }

    public function numberMatches(float $userValue, float $matchValue): bool
    {
        return $userValue <= $matchValue;
    }

    public function boolMatches(bool $userValue, bool $matchValue): bool
    {
        return false;
    }

    public function versionMatches(Version $userValue, Version $matchValue): bool
    {
        return $userValue->compareTo($matchValue) <= 0;
    }
}
