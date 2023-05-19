<?php

namespace Hackle\Internal\Evaluation\Evaluator;

use Hackle\Internal\User\HackleUser;
use Hackle\Internal\Workspace\Workspace;

interface EvaluatorRequest
{
    function getKey(): EvaluatorKey;

    function getWorkspace(): Workspace;

    function getUser(): HackleUser;
}
