<?php

namespace Hackle\Tests\Internal\Evaluation\Action;

use Hackle\Internal\Evaluation\Action\ActionResolver;
use Hackle\Internal\Evaluation\Bucket\Bucketer;
use Hackle\Internal\Model\Bucket;
use Hackle\Internal\Model\Slot;
use Hackle\Internal\Model\TargetAction;
use Hackle\Internal\Model\TargetActionBucket;
use Hackle\Internal\Model\TargetActionVariation;
use Hackle\Internal\Workspace\Workspace;
use Hackle\Tests\Internal\Model\Models;
use Mockery;
use PHPUnit\Framework\TestCase;

class ActionResolverTest extends TestCase
{
    private $bucketer;
    private $sut;

    protected function setUp()
    {
        $this->bucketer = Mockery::mock(Bucketer::class);
        $this->sut = new ActionResolver($this->bucketer);
    }

    public function test__Variation__resolved()
    {
        // given
        $action = new TargetActionVariation(2);
        $experiment = Models::experiment(["id" => 42]);
        $variation = $experiment->getVariationOrNullById(2);

        $request = Models::experimentRequest($experiment);


        // when
        $actual = $this->sut->resolveOrNull($request, $action);

        // then
        $this->assertNotNull($actual);
        $this->assertEquals($variation, $actual);
    }

    public function test__Variation__variation_not_found()
    {
        // given
        $action = new TargetActionVariation(42);
        $experiment = Models::experiment(["id" => 42]);
        $request = Models::experimentRequest($experiment);

        // when

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Variation[42]");

        $this->sut->resolveOrNull($request, $action);
    }

    public function test__Bucket__bucket_not_found()
    {
        // given
        $action = new TargetActionBucket(42);
        $request = Models::experimentRequest();

        // when
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Bucket[42]");

        $this->sut->resolveOrNull($request, $action);
    }

    public function test__Bucket__identifier_not_found()
    {
        // given
        $action = new TargetActionBucket(42);
        $experiment = Models::experiment(["identifierType" => "customId"]);

        $workspace = $this->getMockBuilder(Workspace::class)
            ->getMock();

        $bucket = $this->getMockBuilder(Bucket::class)
            ->disableOriginalConstructor()
            ->getMock();
        $workspace->method('getBucketOrNull')->willReturn($bucket);

        $request = Models::experimentRequest($experiment, $workspace);

        // when
        $actual = $this->sut->resolveOrNull($request, $action);

        // then
        self::assertNull($actual);
    }

    public function test__Bucket__not_allocated()
    {
        // given
        $action = new TargetActionBucket(42);

        $bucket = Mockery::mock(Bucket::class);
        $experiment = Models::experiment();

        $workspace = Mockery::mock(Workspace::class);
        $workspace->allows("getBucketOrNull")->andReturn($bucket);

        $request = Models::experimentRequest($experiment, $workspace);

        $this->bucketer->allows(["bucketing" => null]);

        // when
        $actual = $this->sut->resolveOrNull($request, $action);

        // then
        self::assertNull($actual);
    }

    public function test__Bucket__allocated()
    {
        $action = new TargetActionBucket(42);

        $bucket = Mockery::mock(Bucket::class);
        $experiment = Models::experiment();
        $variation = $experiment->getVariationOrNullById(2);

        $workspace = Mockery::mock(Workspace::class);
        $workspace->allows("getBucketOrNull")->andReturn($bucket);

        $request = Models::experimentRequest($experiment, $workspace);

        $slot = new Slot(0, 100, 2);
        $this->bucketer->allows(["bucketing" => $slot]);

        // when
        $actual = $this->sut->resolveOrNull($request, $action);

        // then
        self::assertEquals($variation, $actual);
    }

    public function test__unsupported_action()
    {
        $actual = $this->sut->resolveOrNull(Models::experimentRequest(), Mockery::mock(TargetAction::class));
        self::assertNull($actual);
    }
}
