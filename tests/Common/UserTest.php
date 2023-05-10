<?php

namespace Hackle\Tests;

use Hackle\Common\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserBuild()
    {
        $user = User::builder()
            ->id("test_id")
            ->property("int_key", 42)
            ->property("float_key", 42.0)
            ->property("bool_key", true)
            ->property("string_key", "abc 123")
            ->property("string_null", null)
            ->build();

        $this->assertEquals("test_id", $user->getId());
        $this->assertEquals(array(
            "int_key" => 42,
            "float_key" => 42.0,
            "bool_key" => true,
            "string_key" => "abc 123"
        ), $user->getProperties());
    }

    public function testUserBuild2()
    {
        $user = User::builder()
            ->id("id")
            ->userId("userId")
            ->deviceId("deviceId")
            ->identifier("id1", "v1")
            ->identifiers(array("id2" => "v2"))
            ->identifiers(null)
            ->property("k1", "v1")
            ->properties(array("k2" => 2))
            ->properties(null)
            ->build();

        $this->assertEquals("id", $user->getId());
        $this->assertEquals("userId", $user->getUserId());
        $this->assertEquals("deviceId", $user->getDeviceId());
        $this->assertEquals(array(
            "id1" => "v1",
            "id2" => "v2"
        ), $user->getIdentifiers());
        $this->assertEquals(array(
            "k1" => "v1",
            "k2" => 2
        ), $user->getProperties());
    }
}
