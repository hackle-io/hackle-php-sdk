<?php

namespace Hackle\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcher;
use Hackle\Internal\Lang\Objects;

final class VersionMatcher implements ValueMatcher
{
    public function matches(OperatorMatcher $operatorMatcher, $userValue, $matchValue): bool
    {
        $typedUserValue = Objects::asVersionOrNull($userValue);
        if ($typedUserValue === null) {
            return false;
        }

        $typedMatchValue = Objects::asVersionOrNull($matchValue);
        if ($typedMatchValue === null) {
            return false;
        }

        return $operatorMatcher->versionMatches($typedUserValue, $typedMatchValue);
    }
}