<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Operator;

use Hackle\Internal\Evaluation\Match\Operator\ContainsMatcher;
use Hackle\Internal\Evaluation\Match\Operator\EndsWithMatcher;
use Hackle\Internal\Evaluation\Match\Operator\GreaterThanMatcher;
use Hackle\Internal\Evaluation\Match\Operator\GreaterThanOrEqualMatcher;
use Hackle\Internal\Evaluation\Match\Operator\InMatcher;
use Hackle\Internal\Evaluation\Match\Operator\LessThanMatcher;
use Hackle\Internal\Evaluation\Match\Operator\LessThanOrEqualMatcher;
use Hackle\Internal\Evaluation\Match\Operator\OperatorMatcherFactory;
use Hackle\Internal\Evaluation\Match\Operator\StartsWithMatcher;
use Hackle\Internal\Model\MatchOperator;
use PHPUnit\Framework\TestCase;

class OperatorMatcherFactoryTest extends TestCase
{

    public function test__getMatcher()
    {
        $sut = new OperatorMatcherFactory();

        self::assertInstanceOf(InMatcher::class, $sut->getMatcher(MatchOperator::IN()));
        self::assertInstanceOf(ContainsMatcher::class, $sut->getMatcher(MatchOperator::CONTAINS()));
        self::assertInstanceOf(StartsWithMatcher::class, $sut->getMatcher(MatchOperator::STARTS_WITH()));
        self::assertInstanceOf(EndsWithMatcher::class, $sut->getMatcher(MatchOperator::ENDS_WITH()));
        self::assertInstanceOf(GreaterThanMatcher::class, $sut->getMatcher(MatchOperator::GT()));
        self::assertInstanceOf(GreaterThanOrEqualMatcher::class, $sut->getMatcher(MatchOperator::GTE()));
        self::assertInstanceOf(LessThanMatcher::class, $sut->getMatcher(MatchOperator::LT()));
        self::assertInstanceOf(LessThanOrEqualMatcher::class, $sut->getMatcher(MatchOperator::LTE()));
    }
}
