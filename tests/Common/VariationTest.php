<?php

namespace Hackle\Tests\Common;

use Hackle\Common\Variation;
use PHPUnit\Framework\TestCase;

class VariationTest extends TestCase
{
    public function testControl()
    {
        self::assertEquals(Variation::A, Variation::getControl());
    }
}