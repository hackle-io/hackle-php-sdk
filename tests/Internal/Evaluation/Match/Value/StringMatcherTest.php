<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\InMatcher;
use Hackle\Internal\Evaluation\Match\Value\StringMatcher;
use PHPUnit\Framework\TestCase;

class StringMatcherTest extends TestCase
{
    private $sut;

    protected function setUp()
    {
        $this->sut = new StringMatcher();
    }

    public function test_string_type_match()
    {
        $this->assert(true, "42", "42");
        $this->assert(false, "42", "43");
    }

    public function test_when_number_type_then_cast_to_string()
    {
        $this->assert(true, "42", 42);
        $this->assert(true, 42, "42");
        $this->assert(true, 42, 42);

        $this->assert(true, "42.42", 42.42);
        $this->assert(true, 42.42, "42.42");
        $this->assert(true, 42.42, 42.42);
    }

    public function test_unsupported_type()
    {
        $this->assert(false, true, true);
        $this->assert(false, true, "1");
        $this->assert(false, "1", true);
    }

    private function assert(bool $expected, $userValue, $matchValue)
    {
        self::assertEquals($expected, $this->sut->matches(new InMatcher(), $userValue, $matchValue));
    }
}
