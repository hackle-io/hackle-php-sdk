<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Operator;

use Hackle\Internal\Evaluation\Match\Operator\ContainsMatcher;
use Hackle\Internal\Evaluation\Match\Operator\EndsWithMatcher;
use Hackle\Internal\Evaluation\Match\Operator\GreaterThanMatcher;
use Hackle\Internal\Evaluation\Match\Operator\GreaterThanOrEqualMatcher;
use Hackle\Internal\Evaluation\Match\Operator\InMatcher;
use Hackle\Internal\Evaluation\Match\Operator\LessThanMatcher;
use Hackle\Internal\Evaluation\Match\Operator\LessThanOrEqualMatcher;
use Hackle\Internal\Evaluation\Match\Operator\StartsWithMatcher;
use Hackle\Internal\Model\Version;
use PHPUnit\Framework\TestCase;

class OperatorMatcherTest extends TestCase
{
    public function test_InMatcher()
    {
        $sut = new InMatcher();

        // String
        self::assertTrue($sut->stringMatches("42", "42"));
        self::assertFalse($sut->stringMatches("42", "43"));

        // Number
        self::assertTrue($sut->numberMatches(42, 42));
        self::assertTrue($sut->numberMatches(42, 42.0));
        self::assertTrue($sut->numberMatches(42.0, 42));
        self::assertTrue($sut->numberMatches(42.42, 42.42));
        self::assertFalse($sut->numberMatches(42, 43));

        // Bool
        self::assertTrue($sut->boolMatches(true, true));
        self::assertTrue($sut->boolMatches(false, false));
        self::assertFalse($sut->boolMatches(true, false));
        self::assertFalse($sut->boolMatches(false, true));

        // Version
        self::assertTrue($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("1.0.0")));
        self::assertFalse($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("2.0.0")));
    }

    public function test_ContainsMatcher()
    {
        $sut = new ContainsMatcher();

        // String
        self::assertTrue($sut->stringMatches("abc", "abc"));
        self::assertTrue($sut->stringMatches("abc", "a"));
        self::assertTrue($sut->stringMatches("abc", "b"));
        self::assertTrue($sut->stringMatches("abc", "c"));
        self::assertTrue($sut->stringMatches("abc", "ab"));
        self::assertFalse($sut->stringMatches("abc", "ac"));
        self::assertFalse($sut->stringMatches("a", "ab"));

        // Number
        self::assertFalse($sut->numberMatches(1, 1));
        self::assertFalse($sut->numberMatches(11, 1));
        self::assertFalse($sut->numberMatches(1, 11));

        // Bool
        self::assertFalse($sut->boolMatches(true, true));
        self::assertFalse($sut->boolMatches(false, false));
        self::assertFalse($sut->boolMatches(true, false));
        self::assertFalse($sut->boolMatches(false, true));

        // Version
        self::assertFalse($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("1.0.0")));
        self::assertFalse($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("2.0.0")));
    }

    public function test_StartsWithMatcher()
    {
        $sut = new StartsWithMatcher();

        // String
        self::assertTrue($sut->stringMatches("abc", "abc"));
        self::assertTrue($sut->stringMatches("abc", "a"));
        self::assertFalse($sut->stringMatches("abc", "b"));
        self::assertFalse($sut->stringMatches("abc", "c"));
        self::assertTrue($sut->stringMatches("abc", "ab"));
        self::assertFalse($sut->stringMatches("abc", "ac"));
        self::assertFalse($sut->stringMatches("a", "ab"));

        // Number
        self::assertFalse($sut->numberMatches(1, 1));
        self::assertFalse($sut->numberMatches(11, 1));
        self::assertFalse($sut->numberMatches(1, 11));

        // Bool
        self::assertFalse($sut->boolMatches(true, true));
        self::assertFalse($sut->boolMatches(false, false));
        self::assertFalse($sut->boolMatches(true, false));
        self::assertFalse($sut->boolMatches(false, true));

        // Version
        self::assertFalse($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("1.0.0")));
        self::assertFalse($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("2.0.0")));
    }

    public function test_EndWithsMatcher()
    {
        $sut = new EndsWithMatcher();

        // String
        self::assertTrue($sut->stringMatches("abc", "abc"));
        self::assertFalse($sut->stringMatches("abc", "a"));
        self::assertFalse($sut->stringMatches("abc", "b"));
        self::assertTrue($sut->stringMatches("abc", "c"));
        self::assertFalse($sut->stringMatches("abc", "ab"));
        self::assertFalse($sut->stringMatches("abc", "ac"));
        self::assertTrue($sut->stringMatches("abc", "bc"));
        self::assertFalse($sut->stringMatches("a", "ab"));

        // Number
        self::assertFalse($sut->numberMatches(1, 1));
        self::assertFalse($sut->numberMatches(11, 1));
        self::assertFalse($sut->numberMatches(1, 11));

        // Bool
        self::assertFalse($sut->boolMatches(true, true));
        self::assertFalse($sut->boolMatches(false, false));
        self::assertFalse($sut->boolMatches(true, false));
        self::assertFalse($sut->boolMatches(false, true));

        // Version
        self::assertFalse($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("1.0.0")));
        self::assertFalse($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("2.0.0")));
    }

    public function test_GreaterThanMatcher()
    {
        $sut = new GreaterThanMatcher();

        // String
        self::assertFalse($sut->stringMatches("41", "42"));
        self::assertFalse($sut->stringMatches("42", "42"));
        self::assertTrue($sut->stringMatches("43", "42"));

        self::assertFalse($sut->stringMatches("20230114", "20230115"));
        self::assertFalse($sut->stringMatches("20230115", "20230115"));
        self::assertTrue($sut->stringMatches("20230116", "20230115"));

        self::assertFalse($sut->stringMatches("2023-01-14", "2023-01-15"));
        self::assertFalse($sut->stringMatches("2023-01-15", "2023-01-15"));
        self::assertTrue($sut->stringMatches("2023-01-16", "2023-01-15"));

        self::assertFalse($sut->stringMatches("a", "a"));
        self::assertTrue($sut->stringMatches("a", "A"));
        self::assertFalse($sut->stringMatches("A", "a"));
        self::assertTrue($sut->stringMatches("aa", "a"));
        self::assertFalse($sut->stringMatches("a", "aa"));


        // Number
        self::assertTrue($sut->numberMatches(1.001, 1));
        self::assertTrue($sut->numberMatches(2, 1));
        self::assertFalse($sut->numberMatches(1, 1));
        self::assertFalse($sut->numberMatches(1, 2));
        self::assertFalse($sut->numberMatches(0.999, 1));

        // Bool
        self::assertFalse($sut->boolMatches(true, true));
        self::assertFalse($sut->boolMatches(false, false));
        self::assertFalse($sut->boolMatches(true, false));
        self::assertFalse($sut->boolMatches(false, true));

        // Version
        self::assertFalse($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("1.0.0")));
        self::assertFalse($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("2.0.0")));
        self::assertTrue($sut->versionMatches(Version::parseOrNull("2.0.0"), Version::parseOrNull("1.0.0")));
    }

    public function test_GreaterThanOrEqualMatcher()
    {
        $sut = new GreaterThanOrEqualMatcher();

        // String
        self::assertFalse($sut->stringMatches("41", "42"));
        self::assertTrue($sut->stringMatches("42", "42"));
        self::assertTrue($sut->stringMatches("43", "42"));

        self::assertFalse($sut->stringMatches("20230114", "20230115"));
        self::assertTrue($sut->stringMatches("20230115", "20230115"));
        self::assertTrue($sut->stringMatches("20230116", "20230115"));

        self::assertFalse($sut->stringMatches("2023-01-14", "2023-01-15"));
        self::assertTrue($sut->stringMatches("2023-01-15", "2023-01-15"));
        self::assertTrue($sut->stringMatches("2023-01-16", "2023-01-15"));

        self::assertTrue($sut->stringMatches("a", "a"));
        self::assertTrue($sut->stringMatches("a", "A"));
        self::assertFalse($sut->stringMatches("A", "a"));
        self::assertTrue($sut->stringMatches("aa", "a"));
        self::assertFalse($sut->stringMatches("a", "aa"));


        // Number
        self::assertTrue($sut->numberMatches(1.001, 1));
        self::assertTrue($sut->numberMatches(2, 1));
        self::assertTrue($sut->numberMatches(1, 1));
        self::assertFalse($sut->numberMatches(1, 2));
        self::assertFalse($sut->numberMatches(0.999, 1));

        // Bool
        self::assertFalse($sut->boolMatches(true, true));
        self::assertFalse($sut->boolMatches(false, false));
        self::assertFalse($sut->boolMatches(true, false));
        self::assertFalse($sut->boolMatches(false, true));

        // Version
        self::assertTrue($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("1.0.0")));
        self::assertFalse($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("2.0.0")));
        self::assertTrue($sut->versionMatches(Version::parseOrNull("2.0.0"), Version::parseOrNull("1.0.0")));
    }

    public function test_LessThanMatcher()
    {
        $sut = new LessThanMatcher();

        // String
        self::assertTrue($sut->stringMatches("41", "42"));
        self::assertFalse($sut->stringMatches("42", "42"));
        self::assertFalse($sut->stringMatches("43", "42"));

        self::assertTrue($sut->stringMatches("20230114", "20230115"));
        self::assertFalse($sut->stringMatches("20230115", "20230115"));
        self::assertFalse($sut->stringMatches("20230116", "20230115"));

        self::assertTrue($sut->stringMatches("2023-01-14", "2023-01-15"));
        self::assertFalse($sut->stringMatches("2023-01-15", "2023-01-15"));
        self::assertFalse($sut->stringMatches("2023-01-16", "2023-01-15"));

        self::assertFalse($sut->stringMatches("a", "a"));
        self::assertFalse($sut->stringMatches("a", "A"));
        self::assertTrue($sut->stringMatches("A", "a"));
        self::assertFalse($sut->stringMatches("aa", "a"));
        self::assertTrue($sut->stringMatches("a", "aa"));


        // Number
        self::assertFalse($sut->numberMatches(1.001, 1));
        self::assertFalse($sut->numberMatches(2, 1));
        self::assertFalse($sut->numberMatches(1, 1));
        self::assertTrue($sut->numberMatches(1, 2));
        self::assertTrue($sut->numberMatches(0.999, 1));

        // Bool
        self::assertFalse($sut->boolMatches(true, true));
        self::assertFalse($sut->boolMatches(false, false));
        self::assertFalse($sut->boolMatches(true, false));
        self::assertFalse($sut->boolMatches(false, true));

        // Version
        self::assertFalse($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("1.0.0")));
        self::assertTrue($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("2.0.0")));
        self::assertFalse($sut->versionMatches(Version::parseOrNull("2.0.0"), Version::parseOrNull("1.0.0")));
    }

    public function test_LessThanOrEqualMatcher()
    {
        $sut = new LessThanOrEqualMatcher();

        // String
        self::assertTrue($sut->stringMatches("41", "42"));
        self::assertTrue($sut->stringMatches("42", "42"));
        self::assertFalse($sut->stringMatches("43", "42"));

        self::assertTrue($sut->stringMatches("20230114", "20230115"));
        self::assertTrue($sut->stringMatches("20230115", "20230115"));
        self::assertFalse($sut->stringMatches("20230116", "20230115"));

        self::assertTrue($sut->stringMatches("2023-01-14", "2023-01-15"));
        self::assertTrue($sut->stringMatches("2023-01-15", "2023-01-15"));
        self::assertFalse($sut->stringMatches("2023-01-16", "2023-01-15"));

        self::assertTrue($sut->stringMatches("a", "a"));
        self::assertFalse($sut->stringMatches("a", "A"));
        self::assertTrue($sut->stringMatches("A", "a"));
        self::assertFalse($sut->stringMatches("aa", "a"));
        self::assertTrue($sut->stringMatches("a", "aa"));


        // Number
        self::assertFalse($sut->numberMatches(1.001, 1));
        self::assertFalse($sut->numberMatches(2, 1));
        self::assertTrue($sut->numberMatches(1, 1));
        self::assertTrue($sut->numberMatches(1, 2));
        self::assertTrue($sut->numberMatches(0.999, 1));

        // Bool
        self::assertFalse($sut->boolMatches(true, true));
        self::assertFalse($sut->boolMatches(false, false));
        self::assertFalse($sut->boolMatches(true, false));
        self::assertFalse($sut->boolMatches(false, true));

        // Version
        self::assertTrue($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("1.0.0")));
        self::assertTrue($sut->versionMatches(Version::parseOrNull("1.0.0"), Version::parseOrNull("2.0.0")));
        self::assertFalse($sut->versionMatches(Version::parseOrNull("2.0.0"), Version::parseOrNull("1.0.0")));
    }
}
