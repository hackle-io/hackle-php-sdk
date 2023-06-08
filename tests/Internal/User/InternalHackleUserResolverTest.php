<?php

namespace Hackle\Tests\Internal\User;

use Hackle\Common\HackleUser;
use Hackle\Internal\User\InternalHackleUserResolver;
use PHPUnit\Framework\TestCase;

class InternalHackleUserResolverTest extends TestCase
{
    private $sut;

    protected function setUp()
    {
        $this->sut = new InternalHackleUserResolver();
    }

    public function testReturnNullIfNotExistIdentifier()
    {
        $user = HackleUser::builder()->build();
        $actual = $this->sut->resolveOrNull($user);
        self::assertNull($actual);
    }

    public function testResolve()
    {
        $user = HackleUser::builder()
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
