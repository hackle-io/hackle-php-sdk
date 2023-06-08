<?php

namespace Hackle\Tests\Internal\Evaluation\Container;

use Hackle\Internal\Evaluation\Bucket\Bucketer;
use Hackle\Internal\Evaluation\Container\ContainerResolver;
use Hackle\Internal\Model\Bucket;
use Hackle\Internal\Model\Container;
use Hackle\Internal\Model\ContainerGroup;
use Hackle\Internal\Model\Slot;
use Hackle\Tests\Internal\Model\Models;
use Mockery;
use PHPUnit\Framework\TestCase;

class ContainerResolverTest extends TestCase
{
    private $bucketer;
    private $sut;


    public function setUp()
    {
        $this->bucketer = Mockery::mock(Bucketer::class);
        $this->sut = new ContainerResolver($this->bucketer);
    }

    public function test_when_identifier_is_null_then_returns_false()
    {
        // given
        $experiment = Models::experiment(["identifierType" => "customId"]);
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->isUserInContainerGroup($request, Mockery::mock(Container::class));

        // then
        self::assertFalse($actual);
    }

    public function test_when_bucket_is_null_then_throws_exception()
    {
        // given
        $experiment = Models::experiment();
        $workspace = Models::workspace(["experiments" => [$experiment]]);
        $container = new Container(1, 42, []);

        $request = Models::experimentRequest($experiment, $workspace);

        // when
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Bucket[42]");

        $this->sut->isUserInContainerGroup($request, $container);
    }

    public function test_when_not_int_container_then_returns_false()
    {
        // given
        $experiment = Models::experiment();
        $bucket = new Bucket(42, 1, 10000, []);
        $workspace = Models::workspace(["experiments" => [$experiment], "buckets" => [$bucket]]);
        $container = new Container(1, 42, []);

        $this->bucketer->allows()->bucketing($bucket, Mockery::any())->andReturn(null);

        $request = Models::experimentRequest($experiment, $workspace);

        // when
        $actual = $this->sut->isUserInContainerGroup($request, $container);

        // then
        self::assertFalse($actual);
    }

    public function test_when_cannot_found_container_group_then_throws_exception()
    {
        // given
        $experiment = Models::experiment();
        $slot = new Slot(0, 100, 43);
        $bucket = new Bucket(42, 1, 10000, [$slot]);
        $workspace = Models::workspace(["experiments" => [$experiment], "buckets" => [$bucket]]);
        $container = new Container(1, 42, []);

        $this->bucketer->allows()->bucketing($bucket, Mockery::any())->andReturn($slot);

        $request = Models::experimentRequest($experiment, $workspace);

        // when
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ContainerGroup[43]");

        $actual = $this->sut->isUserInContainerGroup($request, $container);
    }

    public function test_when_experiment_in_container_group_then_returns_true()
    {
        // given
        $experiment = Models::experiment(["id" => 320]);
        $slot = new Slot(0, 100, 43);
        $bucket = new Bucket(42, 1, 10000, [$slot]);
        $workspace = Models::workspace(["experiments" => [$experiment], "buckets" => [$bucket]]);
        $container = new Container(1, 42, [new ContainerGroup(43, [320])]);

        $this->bucketer->allows()->bucketing($bucket, Mockery::any())->andReturn($slot);

        $request = Models::experimentRequest($experiment, $workspace);

        // when
        $actual = $this->sut->isUserInContainerGroup($request, $container);

        // then
        self::assertTrue($actual);
    }

    public function test_when_experiment_not_in_container_group_then_returns_false()
    {
        // given
        $experiment = Models::experiment(["id" => 320]);
        $slot = new Slot(0, 100, 43);
        $bucket = new Bucket(42, 1, 10000, [$slot]);
        $workspace = Models::workspace(["experiments" => [$experiment], "buckets" => [$bucket]]);
        $container = new Container(1, 42, [new ContainerGroup(43, [3200])]);

        $this->bucketer->allows()->bucketing($bucket, Mockery::any())->andReturn($slot);

        $request = Models::experimentRequest($experiment, $workspace);

        // when
        $actual = $this->sut->isUserInContainerGroup($request, $container);

        // then
        self::assertFalse($actual);
    }
}
