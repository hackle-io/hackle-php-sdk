<?php

namespace Hackle\Internal\Evaluation\Match\Operator;

use Hackle\Internal\Model\Version;

class ContainsMatcher implements OperatorMatcher
{

    public function stringMatches(string $userValue, string $matchValue): bool
    {
        return strpos($userValue, $matchValue) !== false;
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