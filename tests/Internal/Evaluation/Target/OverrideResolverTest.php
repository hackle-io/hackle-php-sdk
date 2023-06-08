<?php

namespace Hackle\Tests\Internal\Evaluation\Target;

use Hackle\Internal\Evaluation\Action\ActionResolver;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Match\TargetMatcher;
use Hackle\Internal\Evaluation\Target\OverrideResolver;
use Hackle\Internal\Model\TargetRule;
use Hackle\Internal\User\InternalHackleUser;
use Hackle\Internal\User\IdentifierType;
use Hackle\Internal\Workspace\DefaultWorkspace;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class OverrideResolverTest extends TestCase
{
    private $targetMatcher;
    private $actionResolver;
    private $sut;

    private $segmentReturns;
    private $segmentCallCount;

    protected function setUp()
    {
        $this->targetMatcher = $this->createMock(TargetMatcher::class);
        $this->actionResolver = $this->createMock(ActionResolver::class);
        $this->sut = new OverrideResolver($this->targetMatcher, $this->actionResolver);

        $this->segmentReturns = [];
        $this->segmentCallCount = 0;
        $this->targetMatcher->method("matches")->willReturnCallback(function () {
            return $this->segmentReturns[$this->segmentCallCount++];
        });
    }

    public function test__UserOverride__when_identifier_not_found_then_returns_null()
    {
        // given
        $user = InternalHackleUser::builder()->identifier(IdentifierType::ID(), "test")->build();
        $experiment = Models::experiment([
            "identifierType" => "customId"
        ]);
        $workspace = DefaultWorkspace::from([]);
        $request = Models::experimentRequest($experiment, $workspace, $user);

        // when
        $actual = $this->sut->resolveOrNull($request, new EvaluatorContext());

        // then
        self::assertNull($actual);
    }

    public function test__UserOverride__when_user_is_not_overridden_then_returns_null()
    {
        $user = InternalHackleUser::builder()->identifier(IdentifierType::ID(), "test")->build();
        $experiment = Models::experiment([
            "identifierType" => "\$id"
        ]);
        $workspace = DefaultWorkspace::from([]);
        $request = Models::experimentRequest($experiment, $workspace, $user);

        // when
        $actual = $this->sut->resolveOrNull($request, new EvaluatorContext());

        // then
        self::assertNull($actual);
    }

    public function test__UserOverride__when_user_is_overridden_then_returns_overridden_variation()
    {
        $user = InternalHackleUser::builder()->identifier(IdentifierType::ID(), "test")->build();
        $experiment = Models::experiment([
            "variations" => [
                Models::variation(42, "A"),
                Models::variation(43, "B")
            ],
            "identifierType" => "\$id",
            "userOverrides" => [
                "test" => 43
            ]
        ]);
        $workspace = DefaultWorkspace::from([]);
        $request = Models::experimentRequest($experiment, $workspace, $user);

        // when
        $actual = $this->sut->resolveOrNull($request, new EvaluatorContext());

        // then
        self::assertNotNull($actual);
        self::assertSame($experiment->getVariationOrNullById(43), $actual);
    }

    public function test__SegmentOverride__when_segment_overrides_is_empty_then_returns_null()
    {
        // given
        $user = InternalHackleUser::builder()->identifier(IdentifierType::ID(), "test")->build();
        $experiment = Models::experiment([
            "variations" => [
                Models::variation(42, "A"),
                Models::variation(43, "B")
            ],
            "identifierType" => "\$id"
        ]);
        $workspace = DefaultWorkspace::from([]);
        $request = Models::experimentRequest($experiment, $workspace, $user);

        // when
        $actual = $this->sut->resolveOrNull($request, new EvaluatorContext());

        // then
        self::assertNull($actual);
    }

    public function test__SegmentOverride__when_user_is_in_segment_then_returns_variation_of_first_matched_segment()
    {
        // given
        $user = InternalHackleUser::builder()->identifier(IdentifierType::ID(), "test")->build();
        $experiment = Models::experiment([
            "segmentOverrides" => [
                $this->createSegmentOverride(false),
                $this->createSegmentOverride(false),
                $this->createSegmentOverride(false),
                $this->createSegmentOverride(true),
                $this->createSegmentOverride(false)
            ]
        ]);
        $workspace = DefaultWorkspace::from([]);
        $request = Models::experimentRequest($experiment, $workspace, $user);

        $variation = $experiment->getVariations()[1];
        $this->actionResolver->method("resolveOrNull")->willReturn($variation);

        // when
        $actual = $this->sut->resolveOrNull($request, new EvaluatorContext());

        // then
        self::assertSame($variation, $actual);
        self::assertEquals(4, $this->segmentCallCount);
    }

    public function test__SegmentOverride__when_user_is_not_in_any_segment_then_returns_null()
    {
        // given
        $user = InternalHackleUser::builder()->identifier(IdentifierType::ID(), "test")->build();
        $experiment = Models::experiment([
            "segmentOverrides" => [
                $this->createSegmentOverride(false),
                $this->createSegmentOverride(false),
                $this->createSegmentOverride(false),
                $this->createSegmentOverride(false),
                $this->createSegmentOverride(false)
            ]
        ]);
        $workspace = DefaultWorkspace::from([]);
        $request = Models::experimentRequest($experiment, $workspace, $user);

        // when
        $actual = $this->sut->resolveOrNull($request, new EvaluatorContext());

        // then
        self::assertNull($actual);
        self::assertEquals(5, $this->segmentCallCount);
    }

    private function createSegmentOverride(bool $isMatch): TargetRule
    {
        $this->segmentReturns[] = $isMatch;
        return $this->createMock(TargetRule::class);
    }
}
