<?php

namespace Hackle\Internal\Evaluation\Evaluator;

use Hackle\Internal\User\InternalHackleUser;
use Hackle\Internal\Workspace\Workspace;

interface EvaluatorRequest
{
    public function getKey(): EvaluatorKey;

    public function getWorkspace(): Workspace;

    public function getUser(): InternalHackleUser;
}
