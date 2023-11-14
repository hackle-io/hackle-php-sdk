<?php

namespace Hackle\Internal\Workspace\Sync;

use Hackle\Internal\Workspace\Workspace;

class WorkspaceConfig
{
    /**
     * @var int
     */
    private $syncAt;

    /**
     * @var ?Workspace
     */
    private $workspace;

    /**
     * @param int $syncAt
     * @param Workspace|null $workspace
     */
    public function __construct(int $syncAt, ?Workspace $workspace)
    {
        $this->syncAt = $syncAt;
        $this->workspace = $workspace;
    }

    public function getSyncAt(): int
    {
        return $this->syncAt;
    }

    public function getWorkspace(): ?Workspace
    {
        return $this->workspace;
    }
}
