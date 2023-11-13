<?php

namespace Hackle\Tests\Internal\Workspace\Sync;

use Hackle\Internal\Workspace\DefaultWorkspace;
use Hackle\Internal\Workspace\Sync\SynchronizeWorkspaceFetcher;
use Hackle\Internal\Workspace\Sync\WorkspaceConfig;
use Hackle\Internal\Workspace\Sync\WorkspaceSynchronizeManager;
use Hackle\Tests\Internal\Time\FixedClock;
use Mockery;
use PHPUnit\Framework\TestCase;

class SynchronizeWorkspaceFetcherTest extends TestCase
{

    public function test__when_workspace_not_cached_then_sync()
    {
        // given
        $workspace = DefaultWorkspace::from(array());
        $synchronizeManager = Mockery::mock(WorkspaceSynchronizeManager::class);
        $synchronizeManager->allows("load")->andReturn(null);
        $synchronizeManager->allows("sync")->andReturn(new WorkspaceConfig(320, $workspace));
        $sut = new SynchronizeWorkspaceFetcher(
            new FixedClock(42, 42),
            $synchronizeManager,
            10000
        );

        // when
        $actual = $sut->fetch();

        // then
        self::assertSame($workspace, $actual);
    }

    public function test__when_workspace_is_cache_but_invalid_then_sync()
    {
        // given
        $synchronizeManager = Mockery::mock(WorkspaceSynchronizeManager::class);

        $cacheWorkspace = DefaultWorkspace::from(array());
        $synchronizeManager->allows("load")->andReturn(new WorkspaceConfig(42, $cacheWorkspace));

        $syncedConfig = DefaultWorkspace::from(array());
        $synchronizeManager->allows("sync")->andReturn(new WorkspaceConfig(10000, $syncedConfig));
        $sut = new SynchronizeWorkspaceFetcher(
            new FixedClock(1042, 42),
            $synchronizeManager,
            999
        );

        // when
        $actual = $sut->fetch();

        // then
        self::assertSame($syncedConfig, $actual);
    }


    public function test__when_workspace_is_cached_then_return_cache_workspace()
    {
        // given
        $synchronizeManager = Mockery::mock(WorkspaceSynchronizeManager::class);

        $cacheWorkspace = DefaultWorkspace::from(array());
        $synchronizeManager->allows("load")->andReturn(new WorkspaceConfig(42, $cacheWorkspace));

        $sut = new SynchronizeWorkspaceFetcher(
            new FixedClock(1042, 42),
            $synchronizeManager,
            1000
        );

        // when
        $actual = $sut->fetch();

        // then
        self::assertSame($cacheWorkspace, $actual);
    }

    public function test__when_current_workspace_is_not_null_but_invalid_then_sync()
    {
        // given
        $synchronizeManager = Mockery::mock(WorkspaceSynchronizeManager::class);

        $synchronizeManager->allows("load")->andReturn(null);

        $workspace1 = DefaultWorkspace::from(array());
        $workspace2 = DefaultWorkspace::from(array());

        $synchronizeManager->allows("sync")->andReturn(
            new WorkspaceConfig(100, $workspace1),
            new WorkspaceConfig(200, $workspace2)
        );

        $sut = new SynchronizeWorkspaceFetcher(
            new FixedClock(131, 131),
            $synchronizeManager,
            30
        );

        self::assertSame($workspace1, $sut->fetch());
        self::assertSame($workspace2, $sut->fetch());
    }

    public function test__when_current_workspace_is_valid_then_return_current_workspace()
    {
        // given
        $synchronizeManager = Mockery::mock(WorkspaceSynchronizeManager::class);

        $synchronizeManager->allows("load")->andReturn(null);

        $workspace1 = DefaultWorkspace::from(array());
        $workspace2 = DefaultWorkspace::from(array());

        $synchronizeManager->allows("sync")->andReturn(
            new WorkspaceConfig(100, $workspace1),
            new WorkspaceConfig(200, $workspace2)
        );

        $sut = new SynchronizeWorkspaceFetcher(
            new FixedClock(130, 130),
            $synchronizeManager,
            30
        );

        self::assertSame($workspace1, $sut->fetch());
        self::assertSame($workspace1, $sut->fetch());
    }
}
