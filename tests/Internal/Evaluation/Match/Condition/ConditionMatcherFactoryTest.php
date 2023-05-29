<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Condition;

use Hackle\Internal\Evaluation\Evaluator\Evaluator;
use Hackle\Internal\Evaluation\Match\Condition\ConditionMatcherFactory;
use Hackle\Internal\Evaluation\Match\Condition\Experiment\ExperimentConditionMatcher;
use Hackle\Internal\Evaluation\Match\Condition\Segment\SegmentConditionMatcher;
use Hackle\Internal\Evaluation\Match\Condition\User\UserConditionMatcher;
use Hackle\Internal\Model\TargetKeyType;
use PHPUnit\Framework\TestCase;

class ConditionMatcherFactoryTest extends TestCase
{
    public function test__getMatcher()
    {
        $sut = new ConditionMatcherFactory($this->createMock(Evaluator::class));

        self::assertInstanceOf(UserConditionMatcher::class, $sut->getMatcher(TargetKeyType::USER_ID()));
        self::assertInstanceOf(UserConditionMatcher::class, $sut->getMatcher(TargetKeyType::USER_PROPERTY()));
        self::assertInstanceOf(UserConditionMatcher::class, $sut->getMatcher(TargetKeyType::HACKLE_PROPERTY()));
        self::assertInstanceOf(SegmentConditionMatcher::class, $sut->getMatcher(TargetKeyType::SEGMENT()));
        self::assertInstanceOf(ExperimentConditionMatcher::class, $sut->getMatcher(TargetKeyType::AB_TEST()));
        self::assertInstanceOf(ExperimentConditionMatcher::class, $sut->getMatcher(TargetKeyType::FEATURE_FLAG()));
    }
}
