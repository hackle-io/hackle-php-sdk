<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Condition\Segment;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\Segment\SegmentMatcher;
use Hackle\Internal\Evaluation\Match\Condition\User\UserConditionMatcher;
use Hackle\Internal\Model\Segment;
use Hackle\Internal\Model\SegmentType;
use Hackle\Internal\Model\Target;
use Hackle\Internal\Model\TargetCondition;
use PHPUnit\Framework\TestCase;

class SegmentMatcherTest extends TestCase
{
    private $userConditionMatcher;
    private $sut;

    protected function setUp()
    {
        $this->userConditionMatcher = $this->createMock(UserConditionMatcher::class);
        $this->sut = new SegmentMatcher($this->userConditionMatcher);
    }

    public function test_when_targets_is_empty_then_return_false()
    {
        // given
        $segment = new Segment(1, "seg1", SegmentType::USER_PROPERTY(), []);
        $request = $this->createMock(EvaluatorRequest::class);

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $segment);

        // then
        self::assertFalse($actual);
    }

    public function test_when_any_of_target_match_then_return_true()
    {
        // given
        $segment = $this->segment(
            [true, true, true, false], // false
            [false], // false
            [true, true] // true
        );
        $request = $this->createMock(EvaluatorRequest::class);


        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $segment);

        // then
        self::assertTrue($actual);
    }

    public function test_all_target_do_not_match_then_return_false()
    {
        // given
        $segment = $this->segment(
            [true, true, true, false], // false
            [false], // false
            [false, true] // false
        );
        $request = $this->createMock(EvaluatorRequest::class);


        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $segment);

        // then
        self::assertFalse($actual);
    }

    private function segment(array ...$targetConditions): Segment
    {
        $matches = [];
        $targets = [];
        foreach ($targetConditions as $targetMatches) {
            $conditions = [];
            foreach ($targetMatches as $targetMatch) {
                $condition = $this->createMock(TargetCondition::class);
                $conditions[] = $condition;
                $matches[] = $targetMatch;
            }
            $targets[] = new Target($conditions);
        }
        $this->userConditionMatcher->method("matches")->will($this->onConsecutiveCalls(...$matches));
        return new Segment(42, "seg", SegmentType::USER_PROPERTY(), $targets);
    }
}
