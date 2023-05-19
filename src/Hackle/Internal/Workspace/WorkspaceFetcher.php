<?php

namespace Hackle\Internal\Workspace;

interface WorkspaceFetcher
{
    public function fetch(): ?Workspace;
}