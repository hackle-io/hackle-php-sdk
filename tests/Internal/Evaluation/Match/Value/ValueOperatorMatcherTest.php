<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcherFactory;
use Hackle\Internal\Evaluation\Match\Value\ValueMatcherFactory;
use Hackle\Internal\Evaluation\Match\Value\ValueOperatorMatcher;
use Hackle\Internal\Model\MatchOperator;
use Hackle\Internal\Model\MatchType;
use Hackle\Internal\Model\TargetMatch;
use Hackle\Internal\Model\ValueType;
use PHPUnit\Framework\TestCase;

class ValueOperatorMatcherTest extends TestCase
{
    private $sut;

    protected function setUp()
    {
        $this->sut = new ValueOperatorMatcher(new ValueMatcherFactory(), new OperatorMatcherFactory());
    }

    public function test_when_any_match_values_then_returns_true()
    {
        // given
        $match = new TargetMatch(
            MatchType::MATCH(),
            MatchOperator::IN(),
            ValueType::NUMBER(),
            [1, 2, 3]
        );

        // when
        $actual = $this->sut->matches(3, $match);

        // then
        self::assertTrue($actual);
    }

    public function test_when_none_match_then_returns_false()
    {
        // given
        $match = new TargetMatch(
            MatchType::MATCH(),
            MatchOperator::IN(),
            ValueType::NUMBER(),
            [1, 2, 3]
        );

        // when
        $actual = $this->sut->matches(4, $match);

        // then
        self::assertFalse($actual);
    }

    public function test_when_matched_with_NOT_MATCH_type_then_return_false()
    {
        // given
        $match = new TargetMatch(
            MatchType::NOT_MATCH(),
            MatchOperator::IN(),
            ValueType::NUMBER(),
            [1, 2, 3]
        );

        // when
        $actual = $this->sut->matches(3, $match);

        // then
        self::assertFalse($actual);
    }

    public function test_when_none_match_with_NOT_MATCH_type_then_return_true()
    {
        // given
        $match = new TargetMatch(
            MatchType::NOT_MATCH(),
            MatchOperator::IN(),
            ValueType::NUMBER(),
            [1, 2, 3]
        );

        // when
        $actual = $this->sut->matches(4, $match);

        // then
        self::assertTrue($actual);
    }

    public function test_when_user_value_is_array_and_any_match_then_returns_true()
    {
        // given
        $match = new TargetMatch(
            MatchType::MATCH(),
            MatchOperator::IN(),
            ValueType::NUMBER(),
            [1, 2, 3]
        );

        // when
        $actual = $this->sut->matches([-1, 0, 1], $match);

        // then
        self::assertTrue($actual);
    }

    public function test_when_user_value_is_array_none_match_then_returns_false()
    {
        // given
        $match = new TargetMatch(
            MatchType::MATCH(),
            MatchOperator::IN(),
            ValueType::NUMBER(),
            [1, 2, 3]
        );

        // when
        $actual = $this->sut->matches([4, 5, 6], $match);

        // then
        self::assertFalse($actual);
    }

    public function test_when_user_value_is_empty_array_then_returns_false()
    {
        // given
        $match = new TargetMatch(
            MatchType::MATCH(),
            MatchOperator::IN(),
            ValueType::NUMBER(),
            [1, 2, 3]
        );

        // when
        $actual = $this->sut->matches([], $match);

        // then
        self::assertFalse($actual);
    }
}
