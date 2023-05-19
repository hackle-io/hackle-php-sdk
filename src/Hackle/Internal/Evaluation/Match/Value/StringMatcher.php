<?php

namespace Hackle\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcher;
use Hackle\Internal\Lang\Objects;

final class StringMatcher implements ValueMatcher
{
    public function matches(OperatorMatcher $operatorMatcher, $userValue, $matchValue): bool
    {
        $typedUserValue = Objects::asStringOrNull($userValue);
        if ($typedUserValue === null) {
            return false;
        }

        $typedMatchValue = Objects::asStringOrNull($matchValue);
        if ($typedMatchValue === null) {
            return false;
        }

        return $operatorMatcher->stringMatches($typedUserValue, $typedMatchValue);
    }
}
