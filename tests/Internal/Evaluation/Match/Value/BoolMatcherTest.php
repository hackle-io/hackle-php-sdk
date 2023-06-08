<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\InMatcher;
use Hackle\Internal\Evaluation\Match\Value\BoolMatcher;
use PHPUnit\Framework\TestCase;

class BoolMatcherTest extends TestCase
{
    private $sut;

    protected function setUp()
    {
        $this->sut = new BoolMatcher();
    }

    public function test_bool_type()
    {
        $this->assert(true, true, true);
        $this->assert(true, false, false);
        $this->assert(false, true, false);
        $this->assert(false, false, true);
    }

    public function test_unsupported_type()
    {
        $this->assert(false, 1, 1);
        $this->assert(false, 0, 0);
        $this->assert(false, 1, true);
        $this->assert(false, true, 1);
        $this->assert(false, 0, false);
        $this->assert(false, false, 0);
        $this->assert(false, "true", true);
    }

    private function assert(bool $expected, $userValue, $matchValue)
    {
        self::assertEquals($expected, $this->sut->matches(new InMatcher(), $userValue, $matchValue));
    }
}
