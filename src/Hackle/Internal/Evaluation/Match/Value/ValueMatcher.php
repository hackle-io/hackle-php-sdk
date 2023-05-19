<?php

namespace Hackle\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcher;

interface ValueMatcher
{
    public function matches(OperatorMatcher $operatorMatcher, $userValue, $matchValue): bool;
}