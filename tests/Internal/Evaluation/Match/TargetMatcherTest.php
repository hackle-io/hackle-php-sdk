<?php

namespace Hackle\Tests\Internal\Evaluation\Match;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcherFactory;
use Hackle\Internal\Evaluation\Match\TargetMatcher;
use Hackle\Internal\Model\Target;
use Hackle\Internal\Model\TargetCondition;
use Hackle\Tests\Internal\Evaluation\Match\Condition\ConditionMatcherStub;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class TargetMatcherTest extends TestCase
{
    private $conditionMatcherFactory;
    private $sut;

    private $conditionMatcher;

    protected function setUp()
    {
        $this->conditionMatcherFactory = $this->createMock(ConditionMatcherFactory::class);
        $this->sut = new TargetMatcher($this->conditionMatcherFactory);

        $this->conditionMatcher = new ConditionMatcherStub();
        $this->conditionMatcherFactory->method("getMatcher")->willReturn($this->conditionMatcher);
    }

    public function test_when_condition_is_empty_then_returns_true()
    {
        // given
        $request = Models::experimentRequest();
        $target = new Target([]);

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $target);

        // then
        self::assertTrue($actual);
    }

    public function test_all_condition_matches_then_return_true()
    {
        // given
        $request = Models::experimentRequest();
        $target = new Target([
            $this->createCondition(true),
            $this->createCondition(true),
            $this->createCondition(true),
            $this->createCondition(true),
            $this->createCondition(true),
        ]);

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $target);

        // then
        self::assertTrue($actual);
        self::assertEquals(5, $this->conditionMatcher->getCallCount());
    }

    public function test_when_any_of_conditions_not_match_then_return_false()
    {
        // given
        $request = Models::experimentRequest();
        $target = new Target([
            $this->createCondition(true),
            $this->createCondition(true),
            $this->createCondition(true),
            $this->createCondition(false),
            $this->createCondition(true),
        ]);

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $target);

        // then
        self::assertFalse($actual);
        self::assertEquals(4, $this->conditionMatcher->getCallCount());
    }

    private function createCondition(bool $isMatch): TargetCondition
    {
        $this->conditionMatcher->addReturn($isMatch);
        return $this->createMock(TargetCondition::class);
    }
}
