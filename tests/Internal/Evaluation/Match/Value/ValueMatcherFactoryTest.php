<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Value\BoolMatcher;
use Hackle\Internal\Evaluation\Match\Value\NumberMatcher;
use Hackle\Internal\Evaluation\Match\Value\StringMatcher;
use Hackle\Internal\Evaluation\Match\Value\ValueMatcherFactory;
use Hackle\Internal\Evaluation\Match\Value\VersionMatcher;
use Hackle\Internal\Model\ValueType;
use PHPUnit\Framework\TestCase;

class ValueMatcherFactoryTest extends TestCase
{

    public function test__getMatcher()
    {
        $sut = new ValueMatcherFactory();

        self::assertInstanceOf(StringMatcher::class, $sut->getMatcher(ValueType::JSON()));
        self::assertInstanceOf(StringMatcher::class, $sut->getMatcher(ValueType::STRING()));
        self::assertInstanceOf(NumberMatcher::class, $sut->getMatcher(ValueType::NUMBER()));
        self::assertInstanceOf(BoolMatcher::class, $sut->getMatcher(ValueType::BOOLEAN()));
        self::assertInstanceOf(VersionMatcher::class, $sut->getMatcher(ValueType::VERSION()));
    }
}
