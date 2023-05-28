<?php

namespace Hackle\Internal\Evaluation\Evaluator;

use Hackle\Internal\User\HackleUser;
use Hackle\Internal\Workspace\Workspace;

interface EvaluatorRequest
{
    public function getKey(): EvaluatorKey;

    public function getWorkspace(): Workspace;

    public function getUser(): HackleUser;
}
