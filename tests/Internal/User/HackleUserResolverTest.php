<?php

namespace Hackle\Tests\Internal\User;

use Hackle\Common\User;
use Hackle\Internal\User\HackleUserResolver;
use PHPUnit\Framework\TestCase;

class HackleUserResolverTest extends TestCase
{
    private $sut;

    protected function setUp()
    {
        $this->sut = new HackleUserResolver();
    }

    public function testReturnNullIfNotExistIdentifier()
    {
        $user = User::builder()->build();
        $actual = $this->sut->resolveOrNull($user);
        self::assertNull($actual);
    }

    public function testResolve()
    {
        $user = User::builder()
            ->id("id")
            ->userId("userId")
            ->deviceId("deviceId")
            ->identifier("customId", "custom")
            ->property("age", 30)
            ->property("grade", "GOLD")
            ->build();

        $actual = $this->sut->resolveOrNull($user);

        self::assertNotNull($actual);
        self::assertEquals(
            array(
                "\$id" => "id",
                "\$userId"=>"userId",
                "\$deviceId" => "deviceId",
                "customId"=>"custom"
            ),
            $actual->getIdentifiers()
        );

        self::assertEquals(
            array(
                "age" => 30,
                "grade" => "GOLD"
            ),
            $actual->getProperties()
        );

        self::assertCount(0, $actual->getHackleProperties());
    }
}
