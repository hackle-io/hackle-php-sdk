<?php

namespace Internal\Workspace;

use Hackle\Internal\Workspace\DefaultWorkspace;
use Hackle\Internal\Workspace\Workspace;
use Hackle\Internal\Workspace\WorkspaceFetcher;

class ResourcesWorkspaceFetcher implements WorkspaceFetcher
{

    private $workspace;

    public function __construct($resourcesPath)
    {
        $json = file_get_contents($resourcesPath);
        $this->workspace = DefaultWorkspace::from(json_decode($json, true));
    }


    public function fetch(): ?Workspace
    {
        return $this->workspace;
    }
}