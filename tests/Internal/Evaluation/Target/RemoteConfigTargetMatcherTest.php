<?php

namespace Hackle\Tests\Internal\Evaluation\Target;

use Hackle\Internal\Evaluation\Bucket\Bucketer;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Match\TargetMatcher;
use Hackle\Internal\Evaluation\Target\RemoteConfigTargetMatcher;
use Hackle\Internal\Model\Bucket;
use Hackle\Internal\Model\RemoteConfigTargetRule;
use Hackle\Internal\Model\Slot;
use Hackle\Internal\Workspace\Workspace;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class RemoteConfigTargetMatcherTest extends TestCase
{
    private $targetMatcher;
    private $bucketer;
    private $sut;

    protected function setUp()
    {
        $this->targetMatcher = $this->createMock(TargetMatcher::class);
        $this->bucketer = $this->createMock(Bucketer::class);
        $this->sut = new RemoteConfigTargetMatcher($this->targetMatcher, $this->bucketer);
    }

    public function test_when_target_rule_is_does_not_match_then_returns_false()
    {
        // given
        $targetRule = $this->createTargetRule(false);
        $request = Models::remoteConfigRequest();

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $targetRule);

        // then
        self::assertFalse($actual);
    }

    public function test_when_identifier_not_found_then_returns_false()
    {
        // given
        $targetRule = $this->createTargetRule(true);
        $parameter = Models::parameter(["identifierType" => "customId"]);
        $request = Models::remoteConfigRequest(["parameter" => $parameter]);

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $targetRule);

        // then
        self::assertFalse($actual);
    }

    public function test_when_cannot_found_bucket_then_throws_exception()
    {
        // given
        $targetRule = $this->createTargetRule(true, 42);
        $parameter = Models::parameter();
        $request = Models::remoteConfigRequest(["parameter" => $parameter]);

        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Bucket[42]");
        $this->sut->matches($request, new EvaluatorContext(), $targetRule);
    }

    public function test_when_not_allocated_then_returns_false()
    {
        // given
        $targetRule = $this->createTargetRule(true, 42);
        $parameter = Models::parameter();
        $workspace = $this->createMock(Workspace::class);
        $workspace->method("getBucketOrNull")->willReturn($this->createMock(Bucket::class));
        $request = Models::remoteConfigRequest(["parameter" => $parameter, "workspace" => $workspace]);

        $this->bucketer->method("bucketing")->willReturn(null);

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $targetRule);

        // then
        self::assertFalse($actual);
    }

    public function test_when_allocated_then_returns_true()
    {
        // given
        $targetRule = $this->createTargetRule(true, 42);
        $parameter = Models::parameter();
        $workspace = $this->createMock(Workspace::class);
        $workspace->method("getBucketOrNull")->willReturn($this->createMock(Bucket::class));
        $request = Models::remoteConfigRequest(["parameter" => $parameter, "workspace" => $workspace]);

        $this->bucketer->method("bucketing")->willReturn($this->createMock(Slot::class));

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $targetRule);

        // then
        self::assertTrue($actual);
    }

    private function createTargetRule(bool $isMatch, int $bucketId = 0): RemoteConfigTargetRule
    {
        $this->targetMatcher->method("matches")->willReturn($isMatch);
        $targetRule = $this->createMock(RemoteConfigTargetRule::class);
        $targetRule->method("getBucketId")->willReturn($bucketId);
        return $targetRule;
    }
}
