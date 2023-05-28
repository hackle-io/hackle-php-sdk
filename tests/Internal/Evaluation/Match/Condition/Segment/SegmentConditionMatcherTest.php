<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Condition\Segment;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\Segment\SegmentConditionMatcher;
use Hackle\Internal\Evaluation\Match\Condition\Segment\SegmentMatcher;
use Hackle\Internal\Model\MatchOperator;
use Hackle\Internal\Model\MatchType;
use Hackle\Internal\Model\Segment;
use Hackle\Internal\Model\SegmentType;
use Hackle\Internal\Model\TargetCondition;
use Hackle\Internal\Model\TargetKey;
use Hackle\Internal\Model\TargetKeyType;
use Hackle\Internal\Model\TargetMatch;
use Hackle\Internal\Model\ValueType;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class SegmentConditionMatcherTest extends TestCase
{
    private $segmentMatcher;
    private $sut;

    protected function setUp()
    {
        $this->segmentMatcher = $this->createMock(SegmentMatcher::class);
        $this->sut = new SegmentConditionMatcher($this->segmentMatcher);
    }

    public function test_when_not_SEGMENT_type_then_thorws_exception()
    {
        // given
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::USER_PROPERTY(), "age"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::NUMBER(), [42])
        );

        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unsupported TargetKeyType [USER_PROPERTY]");
        $this->sut->matches($this->createMock(EvaluatorRequest::class), new EvaluatorContext(), $condition);
    }

    public function test_when_segment_key_is_not_string_then_throws_exception()
    {
        // given
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::SEGMENT(), "SEGMENT"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::NUMBER(), [42])
        );

        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("SegmentKey[42]");
        $this->sut->matches($this->createMock(EvaluatorRequest::class), new EvaluatorContext(), $condition);
    }

    public function test_when_cannot_found_segment_then_throws_exception()
    {
        // given
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::SEGMENT(), "SEGMENT"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["seg_01"])
        );

        // when, then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Segment[seg_01]");
        $this->sut->matches($this->createMock(EvaluatorRequest::class), new EvaluatorContext(), $condition);
    }

    public function test_when_any_of_segments_match_then_returns_true()
    {
        // given
        $seg1 = new Segment(1, "seg01", SegmentType::USER_PROPERTY(), []);
        $seg2 = new Segment(2, "seg02", SegmentType::USER_PROPERTY(), []);
        $seg3 = new Segment(3, "seg03", SegmentType::USER_PROPERTY(), []);
        $segments = [$seg1, $seg2, $seg3];

        $workspace = Models::workspace(["segments" => $segments]);

        $request = Models::experimentRequest(Models::experiment(), $workspace);

        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::SEGMENT(), "SEGMENT"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["seg01", "seg02", "seg03"])
        );

        $this->segmentMatcher->expects(self::exactly(2))
            ->method("matches")
            ->will($this->onConsecutiveCalls(false, true, false));


        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $condition);

        // then
        self::assertTrue($actual);
    }

    public function test_when_all_segments_do_not_match_then_returns_false()
    {
        // given
        $seg1 = new Segment(1, "seg01", SegmentType::USER_PROPERTY(), []);
        $seg2 = new Segment(2, "seg02", SegmentType::USER_PROPERTY(), []);
        $seg3 = new Segment(3, "seg03", SegmentType::USER_PROPERTY(), []);
        $segments = [$seg1, $seg2, $seg3];

        $workspace = Models::workspace(["segments" => $segments]);

        $request = Models::experimentRequest(Models::experiment(), $workspace);

        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::SEGMENT(), "SEGMENT"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), ["seg01", "seg02", "seg03"])
        );

        $this->segmentMatcher->expects(self::exactly(3))
            ->method("matches")
            ->will($this->onConsecutiveCalls(false, false, false));


        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $condition);

        // then
        self::assertFalse($actual);
    }
}
