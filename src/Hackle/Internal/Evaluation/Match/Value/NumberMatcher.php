<?php

namespace Hackle\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcher;
use Hackle\Internal\Lang\Objects;

final class NumberMatcher implements ValueMatcher
{
    public function matches(OperatorMatcher $operatorMatcher, $userValue, $matchValue): bool
    {
        $typedUserValue = Objects::asFloatOrNull($userValue);
        if ($typedUserValue === null) {
            return false;
        }

        $typedMatchValue = Objects::asFloatOrNull($matchValue);
        if ($typedMatchValue === null) {
            return false;
        }

        return $operatorMatcher->numberMatches($typedUserValue, $typedMatchValue);
    }
}
