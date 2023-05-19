<?php

namespace Hackle\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcher;
use Hackle\Internal\Lang\Objects;

final class BoolMatcher implements ValueMatcher
{
    public function matches(OperatorMatcher $operatorMatcher, $userValue, $matchValue): bool
    {
        $typedUserValue = Objects::asBoolOrNull($userValue);
        if ($typedUserValue === null) {
            return false;
        }

        $typedMatchValue = Objects::asBoolOrNull($matchValue);
        if ($typedMatchValue === null) {
            return false;
        }

        return $operatorMatcher->boolMatches($typedUserValue, $typedMatchValue);
    }
}
