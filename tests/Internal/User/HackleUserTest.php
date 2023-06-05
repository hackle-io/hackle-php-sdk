<?php

namespace Hackle\Tests\Internal\User;

use Hackle\Internal\User\HackleUser;
use Hackle\Internal\User\IdentifierType;
use PHPUnit\Framework\TestCase;

class HackleUserTest extends TestCase
{
    public function testBuild()
    {
        $user = HackleUser::builder()
            ->identifiers(array("type-1" => "value-1"))
            ->identifier(IdentifierType::ID(), "id")
            ->identifier(IdentifierType::USER(), "userId")
            ->identifier(IdentifierType::DEVICE(), "deviceId")
            ->identifier(IdentifierType::HACKLE_DEVICE_ID(), "hackleDeviceId")
            ->identifier(IdentifierType::SESSION(), "sessionId")
            ->properties(array("key-1" => "value-1"))
            ->property("key-2", "value-2")
            ->hackleProperties(array("hkey-1" => "hvalue-1"))
            ->hackleProperty("hkey-2", "hvalue-2")
            ->build();

        self::assertEquals("id", $user->getIdentifiers()[IdentifierType::ID]);
        self::assertEquals("userId", $user->getIdentifiers()[IdentifierType::USER]);
        self::assertEquals("deviceId", $user->getIdentifiers()[IdentifierType::DEVICE]);
        self::assertEquals("sessionId", $user->getIdentifiers()[IdentifierType::SESSION]);

        self::assertEquals(array(
            "type-1" => "value-1",
            "\$id" => "id",
            "\$userId" => "userId",
            "\$deviceId" => "deviceId",
            "\$hackleDeviceId" => "hackleDeviceId",
            "\$sessionId" => "sessionId"), $user->getIdentifiers());

        self::assertEquals(array("key-1" => "value-1", "key-2" => "value-2"), $user->getProperties());
        self::assertEquals(array("hkey-1" => "hvalue-1", "hkey-2" => "hvalue-2"), $user->getHackleProperties());
    }
}
