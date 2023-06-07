<?php

namespace Hackle\Tests\Internal\Model;

use Hackle\Internal\Model\ParameterConfiguration;
use PHPUnit\Framework\TestCase;

class ParameterConfigurationTest extends TestCase
{
    public function testParameterConfiguration()
    {
        $parameterConfiguration = new ParameterConfiguration(
            42,
            array(
                "string_key" => "string_value",
                "empty_string_key" => "",
                "int_key" => 42.0,
                "zero_int_key" => 0,
                "negative_int_key" => -1,
                "max_int_key"=> PHP_INT_MAX,
                "long_key"=> 320.0,
                "long_key2" => 92147483647.0,
                "double_key" => 0.42,
                "true_boolean_key" => true,
                "false_boolean_key" => false
            )
        );

        self::assertEquals(42, $parameterConfiguration->getId());
        self::assertEquals("string_value", $parameterConfiguration->getString("string_key", "!!"));
        self::assertEquals("", $parameterConfiguration->getString("empty_string_key", "!!"));
        self::assertEquals("!!", $parameterConfiguration->getString("invalid_key", "!!"));

        self::assertEquals(42, $parameterConfiguration->getInt("int_key", 999));
        self::assertEquals(0, $parameterConfiguration->getInt("zero_int_key", 999));
        self::assertEquals(-1, $parameterConfiguration->getInt("negative_int_key", 999));
        self::assertEquals(PHP_INT_MAX, $parameterConfiguration->getInt("max_int_key", 999));
        self::assertEquals(999, $parameterConfiguration->getInt("invalid_int_key", 999));
        self::assertEquals(0, $parameterConfiguration->getInt("double_key", 999));

        self::assertEquals(320, $parameterConfiguration->getInt("long_key", 999));
        self::assertEquals(92147483647, $parameterConfiguration->getInt("long_key2", 999));
        self::assertEquals(999, $parameterConfiguration->getInt("invalid_long_key", 999));

        self::assertEquals(0.42, $parameterConfiguration->getFloat("double_key", 99.9));
        self::assertEquals(99.9, $parameterConfiguration->getFloat("invalid_double_key", 99.9));
        self::assertEquals(42.0, $parameterConfiguration->getFloat("int_key", 99.9));

        self::assertTrue($parameterConfiguration->getBool("true_boolean_key", false));
        self::assertFalse($parameterConfiguration->getBool("false_boolean_key", true));
        self::assertTrue($parameterConfiguration->getBool("invalid_boolean_key", true));
    }
}
