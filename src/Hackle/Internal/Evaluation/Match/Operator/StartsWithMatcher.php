<?php

namespace Hackle\Internal\Evaluation\Match\Operator;

use Hackle\Internal\Model\Version;

class StartsWithMatcher implements OperatorMatcher
{

    public function stringMatches(string $userValue, string $matchValue): bool
    {
        return substr($userValue, 0, strlen($matchValue)) === $matchValue;
    }

    public function numberMatches(float $userValue, float $matchValue): bool
    {
        return false;
    }

    public function boolMatches(bool $userValue, bool $matchValue): bool
    {
        return false;
    }

    public function versionMatches(Version $userValue, Version $matchValue): bool
    {
        return false;
    }
}