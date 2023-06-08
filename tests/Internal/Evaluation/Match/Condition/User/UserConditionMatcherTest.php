<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Condition\User;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorRequest;
use Hackle\Internal\Evaluation\Match\Condition\User\UserConditionMatcher;
use Hackle\Internal\Evaluation\Match\Condition\User\UserValueResolver;
use Hackle\Internal\Evaluation\Match\Value\ValueOperatorMatcher;
use Hackle\Internal\Model\MatchOperator;
use Hackle\Internal\Model\MatchType;
use Hackle\Internal\Model\TargetCondition;
use Hackle\Internal\Model\TargetKey;
use Hackle\Internal\Model\TargetKeyType;
use Hackle\Internal\Model\TargetMatch;
use Hackle\Internal\Model\ValueType;
use PHPUnit\Framework\TestCase;

class UserConditionMatcherTest extends TestCase
{
    private $userValueResolver;
    private $valueOperatorMatcher;
    private $sut;

    protected function setUp()
    {
        $this->userValueResolver = $this->createMock(UserValueResolver::class);
        $this->valueOperatorMatcher = $this->createMock(ValueOperatorMatcher::class);
        $this->sut = new UserConditionMatcher($this->userValueResolver, $this->valueOperatorMatcher);
    }


    public function test_when_resolved_user_value_is_null_then_returns_false()
    {
        // given
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::USER_PROPERTY(), "age"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::NUMBER(), [42])
        );
        $this->userValueResolver->method("resolveOrNull")->willReturn(null);
        $request = $this->createMock(EvaluatorRequest::class);

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $condition);

        // then
        self::assertFalse($actual);
    }

    public function test_when_user_value_is_not_null_then_matches_value_operator()
    {
        // given
        $condition = new TargetCondition(
            new TargetKey(TargetKeyType::USER_PROPERTY(), "age"),
            new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::NUMBER(), [42])
        );
        $this->userValueResolver->method("resolveOrNull")->willReturn(30);
        $this->valueOperatorMatcher->method("matches")->willReturn(true);
        $request = $this->createMock(EvaluatorRequest::class);

        // when
        $actual = $this->sut->matches($request, new EvaluatorContext(), $condition);

        // then
        self::assertTrue($actual);
    }
}
