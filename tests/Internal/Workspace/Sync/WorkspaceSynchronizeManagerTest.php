<?php

namespace Hackle\Tests\Internal\Workspace\Sync;

use Hackle\Internal\Model\Sdk;
use Hackle\Internal\Workspace\DefaultWorkspace;
use Hackle\Internal\Workspace\Sync\HttpWorkspaceSynchronizer;
use Hackle\Internal\Workspace\Sync\WorkspaceConfig;
use Hackle\Internal\Workspace\Sync\WorkspaceRepository;
use Hackle\Internal\Workspace\Sync\WorkspaceSynchronizeManager;
use Hackle\Tests\Internal\Repository\MemoryRepository;
use Hackle\Tests\Internal\Time\FixedClock;
use Mockery;
use PHPUnit\Framework\TestCase;

class WorkspaceSynchronizeManagerTest extends TestCase
{
    private $clock;
    private $repository;
    private $httpWorkspaceSynchronizer;
    private $sut;

    public function setUp()
    {
        $this->clock = new FixedClock(42, 42);
        $this->repository = new WorkspaceRepository(new MemoryRepository(), new Sdk("SDK_KEY"));
        $this->httpWorkspaceSynchronizer = Mockery::mock(HttpWorkspaceSynchronizer::class);
        $this->sut = new WorkspaceSynchronizeManager($this->clock, $this->repository, $this->httpWorkspaceSynchronizer);
    }

    public function test__sync__when_failed_to_sync_internal_then_return_null()
    {
        // given
        $this->httpWorkspaceSynchronizer->allows("sync")->andThrow(new \Exception("fail"));

        // when
        $actual = $this->sut->sync();

        // then
        self::assertNull($actual->getWorkspace());
    }

    public function test__sync__update_sync_at()
    {
        // given
        $workspace = DefaultWorkspace::from(array());
        $this->httpWorkspaceSynchronizer->allows("sync")->andReturn($workspace);

        // when
        $actual = $this->sut->sync();

        // then
        self::assertEquals(42, $actual->getSyncAt());
        self::assertNotNull($actual->getWorkspace());
        self::assertEquals(42, $this->repository->getSyncAt());
    }

    public function test__load__when_not_cached_then_return_null()
    {
        $actual = $this->sut->load();
        self::assertNull($actual);
    }

    public function test__load__cache_with_null()
    {
        // given
        $this->repository->setSyncAt(42);

        // when
        $actual = $this->sut->load();

        // then
        self::assertEquals(new WorkspaceConfig(42, null), $actual);
    }

    public function test__load__cached()
    {
        // given
        $this->repository->setSyncAt(42);
        $this->repository->setWorkspace(file_get_contents(__DIR__ . "/../../../Resources/workspace_config.json"));

        // when
        $actual = $this->sut->load();

        // then
        self::assertEquals(42, $actual->getSyncAt());
        self::assertNotNull($actual->getWorkspace());
        self::assertNotNull($actual->getWorkspace()->getExperimentOrNull(5));
    }
}
