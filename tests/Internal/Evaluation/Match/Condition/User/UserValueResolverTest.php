<?php

namespace Hackle\Tests\Internal\Evaluation\Match\Condition\User;

use Hackle\Internal\Evaluation\Match\Condition\User\UserValueResolver;
use Hackle\Internal\Model\TargetKey;
use Hackle\Internal\Model\TargetKeyType;
use Hackle\Internal\User\HackleUser;
use PHPUnit\Framework\TestCase;

class UserValueResolverTest extends TestCase
{

    public function test_resolve_user_value()
    {
        $sut = new UserValueResolver();

        $user = new HackleUser(
            ["id" => "42"],
            ["age" => 42],
            ["osName" => "Android"]
        );

        self::assertEquals(
            "42",
            $sut->resolveOrNull($user, new TargetKey(TargetKeyType::USER_ID(), "id"))
        );
        self::assertEquals(
            42,
            $sut->resolveOrNull($user, new TargetKey(TargetKeyType::USER_PROPERTY(), "age"))
        );
        self::assertEquals(
            "Android",
            $sut->resolveOrNull($user, new TargetKey(TargetKeyType::HACKLE_PROPERTY(), "osName"))
        );


        self::assertNull($sut->resolveOrNull($user, new TargetKey(TargetKeyType::USER_ID(), "id2")));
        self::assertNull($sut->resolveOrNull($user, new TargetKey(TargetKeyType::USER_PROPERTY(), "age2")));
        self::assertNull($sut->resolveOrNull($user, new TargetKey(TargetKeyType::HACKLE_PROPERTY(), "osName2")));
    }

    public function test_unsupported_type()
    {
        $sut = new UserValueResolver();
        $user = HackleUser::builder()->build();

        try {
            $sut->resolveOrNull($user, new TargetKey(TargetKeyType::SEGMENT(), "segment"));
            self::fail();
        } catch (\InvalidArgumentException $e) {
            self::assertEquals("Unsupported TargetKeyType [SEGMENT]", $e->getMessage());
        }

        try {
            $sut->resolveOrNull($user, new TargetKey(TargetKeyType::AB_TEST(), "ab"));
            self::fail();
        } catch (\InvalidArgumentException $e) {
            self::assertEquals("Unsupported TargetKeyType [AB_TEST]", $e->getMessage());
        }

        try {
            $sut->resolveOrNull($user, new TargetKey(TargetKeyType::FEATURE_FLAG(), "ff"));
            self::fail();
        } catch (\InvalidArgumentException $e) {
            self::assertEquals("Unsupported TargetKeyType [FEATURE_FLAG]", $e->getMessage());
        }
    }
}
