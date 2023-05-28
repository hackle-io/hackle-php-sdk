<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\InMatcher;
use Hackle\Internal\Evaluation\Match\Value\NumberMatcher;
use PHPUnit\Framework\TestCase;

class NumberMatcherTest extends TestCase
{

    private $sut;

    protected function setUp()
    {
        $this->sut = new NumberMatcher();
    }

    public function test_number_type()
    {
        $this->assert(true, 42, 42);
        $this->assert(true, 42.42, 42.42);
        $this->assert(true, 42, 42.0);
        $this->assert(true, 42.0, 42);
        $this->assert(true, 0, 0.0);
        $this->assert(true, 0.0, 0.0);
        $this->assert(true, 0.0, 0);
    }

    public function test_string_type()
    {
        $this->assert(true, "42", 42);
        $this->assert(true, 42, "42");
        $this->assert(true, "42", "42");

        $this->assert(true, "42.42", 42.42);
        $this->assert(true, 42.42, "42.42");
        $this->assert(true, "42.42", "42.42");
    }

    public function test_unsupported_type()
    {
        $this->assert(false, "42a", 42);
        $this->assert(false, 0, "false");
        $this->assert(false, 0, false);
        $this->assert(false, true, true);
    }

    private function assert(bool $expected, $userValue, $matchValue)
    {
        self::assertEquals($expected, $this->sut->matches(new InMatcher(), $userValue, $matchValue));
    }
}
