<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Value;

use Hackle\Internal\Evaluation\Match\Operator\InMatcher;
use Hackle\Internal\Evaluation\Match\Value\VersionMatcher;
use PHPUnit\Framework\TestCase;

class VersionMatcherTest extends TestCase
{
    private $sut;

    protected function setUp()
    {
        $this->sut = new VersionMatcher();
    }

    public function test_version_type()
    {
        $this->assert(true, "1.0.0", "1.0.0");
        $this->assert(true, "1", "1");
        $this->assert(false, "1", "2");
    }

    public function test_unsupported_type()
    {
        $this->assert(false, 1, 1);
        $this->assert(false, 1, "1");
        $this->assert(false, "1", 1);
    }

    private function assert(bool $expected, $userValue, $matchValue)
    {
        self::assertEquals($expected, $this->sut->matches(new InMatcher(), $userValue, $matchValue));
    }
}
