<?php

namespace Hackle\Tests\Internal\Model;

use Hackle\Internal\Model\TargetingType;
use Hackle\Internal\Model\TargetKeyType;
use PHPUnit\Framework\TestCase;

class TargetingTypeTest extends TestCase
{
    public function testIdentifier()
    {
        $this->isSupport(TargetKeyType::SEGMENT(), TargetingType::IDENTIFIER());

        $this->isNotSupport(TargetKeyType::USER_ID(), TargetingType::IDENTIFIER());
        $this->isNotSupport(TargetKeyType::USER_PROPERTY(), TargetingType::IDENTIFIER());
        $this->isNotSupport(TargetKeyType::HACKLE_PROPERTY(), TargetingType::IDENTIFIER());
        $this->isNotSupport(TargetKeyType::AB_TEST(), TargetingType::IDENTIFIER());
        $this->isNotSupport(TargetKeyType::FEATURE_FLAG(), TargetingType::IDENTIFIER());
    }

    public function testProperty()
    {
        $this->isSupport(TargetKeyType::SEGMENT(), TargetingType::PROPERTY());
        $this->isSupport(TargetKeyType::USER_PROPERTY(), TargetingType::PROPERTY());
        $this->isSupport(TargetKeyType::HACKLE_PROPERTY(), TargetingType::PROPERTY());
        $this->isSupport(TargetKeyType::AB_TEST(), TargetingType::PROPERTY());
        $this->isSupport(TargetKeyType::FEATURE_FLAG(), TargetingType::PROPERTY());

        $this->isNotSupport(TargetKeyType::USER_ID(), TargetingType::PROPERTY());
    }

    public function testSegment()
    {
        $this->isSupport(TargetKeyType::USER_PROPERTY(), TargetingType::SEGMENT());
        $this->isSupport(TargetKeyType::HACKLE_PROPERTY(), TargetingType::SEGMENT());
        $this->isSupport(TargetKeyType::USER_ID(), TargetingType::SEGMENT());

        $this->isNotSupport(TargetKeyType::SEGMENT(), TargetingType::SEGMENT());
        $this->isNotSupport(TargetKeyType::AB_TEST(), TargetingType::SEGMENT());
        $this->isNotSupport(TargetKeyType::FEATURE_FLAG(), TargetingType::SEGMENT());
    }

    private function isSupport(TargetKeyType $keyType, TargetingType $targetingType)
    {
        self::assertTrue($targetingType->supports($keyType));
    }

    private function isNotSupport(TargetKeyType $keyType, TargetingType $targetingType)
    {
        self::assertFalse($targetingType->supports($keyType));
    }
}
