<?php

namespace Hackle\Internal\Evaluation\Match\Operator;

use Hackle\Internal\Model\Version;

interface OperatorMatcher
{
    public function stringMatches(string $userValue, string $matchValue): bool;

    public function numberMatches(float $userValue, float $matchValue): bool;

    public function boolMatches(bool $userValue, bool $matchValue): bool;

    public function versionMatches(Version $userValue, Version $matchValue): bool;
}

