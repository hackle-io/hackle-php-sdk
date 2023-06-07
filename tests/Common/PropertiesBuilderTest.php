<?php

namespace Hackle\Tests\Common;

use Hackle\Common\PropertiesBuilder;
use Hackle\Common\HackleUser;
use PHPUnit\Framework\TestCase;

class PropertiesBuilderTest extends TestCase
{
    public function testRawValueValidBuild()
    {
        self::assertEquals(array("key1" => 1), (new PropertiesBuilder())->add("key1", 1)->build());
        self::assertEquals(array("key1" => "1"), (new PropertiesBuilder())->add("key1", "1")->build());
        self::assertEquals(array("key1" => true), (new PropertiesBuilder())->add("key1", true)->build());
        self::assertEquals(array("key1" => false), (new PropertiesBuilder())->add("key1", false)->build());
    }

    public function testRawValueInvalid()
    {
        self::assertEmpty((new PropertiesBuilder())->add("key1", HackleUser::of("id"))->build());
    }

    public function testArrayValue()
    {
        self::assertEquals(array("key1" => array(1, 2, 3)), (new PropertiesBuilder())->addAll(array("key1" => array(1, 2, 3)))->build());
        self::assertEquals(array("key1" => array("1", "2", "3")), (new PropertiesBuilder())->addAll(array("key1" => array("1", "2", "3")))->build());
        self::assertEquals(array("key1" => array("1", 2, "3")), (new PropertiesBuilder())->addAll(array("key1" => array("1", 2, "3")))->build());
        self::assertEquals(array("key1" => array(1, 2, 3, 4)), (new PropertiesBuilder())->addAll(array("key1" => array(1, 2, 3, null, 4)))->build());
        self::assertEquals(array("key1" => array()), (new PropertiesBuilder())->addAll(array("key1" => array(true, false)))->build());
        self::assertEquals(array("key1" => array()), (new PropertiesBuilder())->addAll(array("key1" => array(str_repeat("a", 1025))))->build());
    }

    public function testMaxPropertySize128()
    {
        $builder = new PropertiesBuilder();
        for ($i = 1; $i <= 128; $i++) {
            $builder->add(strval($i), $i);
        }
        self::assertCount(128, $builder->build());
        self::assertCount(128, $builder->add("key", 42)->build());
        self::assertArrayNotHasKey("key", $builder->add("key", 42)->build());
    }

    public function testMaxKeyLength128()
    {
        $builder = new PropertiesBuilder();
        self::assertCount(1, $builder->add(str_repeat("a", 128), 128)->build());

        $builder->add(str_repeat("a", 129), 129);
        self::assertCount(1, $builder->build());
    }

    public function testProperties()
    {
        $properties = array(
            "k1" => "v1",
            "k2" => 2,
            "k3" => true,
            "k4" => false,
            "k5" => array(1, 2, 3),
            "k6" => array("1", "2", "3"),
            "k7" => null,
        );
        $actual = (new PropertiesBuilder())->addAll($properties)->build();
        self::assertCount(6, $actual);
        self::assertArrayHasKey("k1", $actual);
        self::assertArrayHasKey("k2", $actual);
        self::assertArrayHasKey("k3", $actual);
        self::assertArrayHasKey("k4", $actual);
        self::assertArrayHasKey("k5", $actual);
        self::assertArrayHasKey("k6", $actual);
        self::assertArrayNotHasKey("k7", $actual);
    }
}
