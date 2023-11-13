<?php

namespace Hackle\Internal\Workspace\Sync;

use Hackle\Internal\Logger\HackleLog;
use Hackle\Internal\Time\Clock;
use Hackle\Internal\Workspace\DefaultWorkspace;
use Hackle\Internal\Workspace\Workspace;
use Throwable;

class WorkspaceSynchronizeManager
{

    /**
     * @var Clock
     */
    private $clock;

    /**
     * @var WorkspaceRepository
     */
    private $repository;

    /**
     * @var HttpWorkspaceSynchronizer
     */
    private $httpWorkspaceSynchronizer;

    /**
     * @param Clock $clock
     * @param WorkspaceRepository $repository
     * @param HttpWorkspaceSynchronizer $httpWorkspaceSynchronizer
     */
    public function __construct(
        Clock $clock,
        WorkspaceRepository $repository,
        HttpWorkspaceSynchronizer $httpWorkspaceSynchronizer
    ) {
        $this->clock = $clock;
        $this->repository = $repository;
        $this->httpWorkspaceSynchronizer = $httpWorkspaceSynchronizer;
    }

    public function sync(): WorkspaceConfig
    {
        $workspace = $this->syncInternal();

        $syncAt = $this->clock->currentMillis();
        $this->repository->setSyncAt($syncAt);

        return new WorkspaceConfig($syncAt, $workspace);
    }

    private function syncInternal(): ?Workspace
    {
        try {
            return $this->httpWorkspaceSynchronizer->sync();
        } catch (Throwable $e) {
            HackleLog::error("Failed to sync workspace: {$e->getMessage()}");
            return null;
        }
    }

    public function load(): ?WorkspaceConfig
    {
        $syncAt = $this->repository->getSyncAt();
        if ($syncAt === null) {
            return null;
        }
        $workspace = $this->loadWorkspace();
        return new WorkspaceConfig($syncAt, $workspace);
    }

    private function loadWorkspace(): ?Workspace
    {
        $json = json_decode($this->repository->getWorkspace(), true);
        if ($json === null) {
            return null;
        }
        return DefaultWorkspace::from($json);
    }
}
