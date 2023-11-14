<?php

namespace Hackle\Internal\Workspace\Sync;

use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\Sdk;
use Hackle\Internal\Repository\Repository;

class WorkspaceRepository
{

    private const WORKSPACE_KEY_PREFIX = "workspace_";
    private const SYNC_AT_KEY_PREFIX = "workspace_sync_at_";
    private const LAST_MODIFIED_KEY_PREFIX = "workspace_last_modified_";

    /**
     * @var Repository
     */
    private $repository;

    private $workspaceKey;
    private $syncAtKey;
    private $lastModifiedKey;

    /**
     * @param Repository $repository
     * @param Sdk $sdk
     */
    public function __construct(Repository $repository, Sdk $sdk)
    {
        $this->repository = $repository;
        $this->workspaceKey = self::WORKSPACE_KEY_PREFIX . $sdk->getKey();
        $this->syncAtKey = self::SYNC_AT_KEY_PREFIX . $sdk->getKey();
        $this->lastModifiedKey = self::LAST_MODIFIED_KEY_PREFIX . $sdk->getKey();
    }

    public function getWorkspace(): ?string
    {
        return $this->repository->get($this->workspaceKey);
    }

    public function setWorkspace(string $json)
    {
        $this->repository->set($this->workspaceKey, $json);
    }

    public function getSyncAt(): ?int
    {
        return Objects::asIntOrNull($this->repository->get($this->syncAtKey));
    }

    public function setSyncAt(int $syncAt)
    {
        $this->repository->set($this->syncAtKey, strval($syncAt));
    }

    public function getLastModified(): ?string
    {
        return $this->repository->get($this->lastModifiedKey);
    }

    public function setLastModified(?string $lastModified)
    {
        $this->repository->set($this->lastModifiedKey, $lastModified);
    }
}
