<?php

namespace Hackle\Internal\Workspace\Sync;

use Hackle\Internal\Time\Clock;
use Hackle\Internal\Workspace\Workspace;
use Hackle\Internal\Workspace\WorkspaceFetcher;

class SynchronizeWorkspaceFetcher implements WorkspaceFetcher
{
    /**
     * @var Clock
     */
    private $clock;

    /**
     * @var WorkspaceSynchronizeManager
     */
    private $synchronizeManager;

    /**
     * @var int
     */
    private $syncIntervalMillis;

    /**
     * @var WorkspaceConfig|null
     */
    private $workspaceConfig;

    /**
     * @param Clock $clock
     * @param WorkspaceSynchronizeManager $synchronizeManager
     * @param int $syncIntervalMillis
     */
    public function __construct(Clock $clock, WorkspaceSynchronizeManager $synchronizeManager, int $syncIntervalMillis)
    {
        $this->clock = $clock;
        $this->synchronizeManager = $synchronizeManager;
        $this->syncIntervalMillis = $syncIntervalMillis;
        $this->workspaceConfig = null;
    }

    public function fetch(): ?Workspace
    {
        $now = $this->clock->currentMillis();

        $currentConfig = $this->workspaceConfig;
        if ($currentConfig !== null && ($now - $currentConfig->getSyncAt()) <= $this->syncIntervalMillis) {
            return $currentConfig->getWorkspace();
        }

        $cachedWorkspace = $this->synchronizeManager->load();
        if ($cachedWorkspace !== null && ($now - $cachedWorkspace->getSyncAt()) <= $this->syncIntervalMillis) {
            $this->workspaceConfig = $cachedWorkspace;
            return $cachedWorkspace->getWorkspace();
        }

        $syncedConfig = $this->synchronizeManager->sync();
        $this->workspaceConfig = $syncedConfig;
        return $syncedConfig->getWorkspace();
    }
}
