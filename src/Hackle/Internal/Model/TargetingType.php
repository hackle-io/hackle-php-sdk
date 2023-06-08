<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Lang\Enum;

/**
 * @method static IDENTIFIER()
 * @method static PROPERTY()
 * @method static SEGMENT()
 */
class TargetingType extends Enum
{
    public const IDENTIFIER = "IDENTIFIER";
    public const PROPERTY = "PROPERTY";
    public const SEGMENT = "SEGMENT";


    private const SUPPORT_TYPES = [
        TargetingType::IDENTIFIER => [
            TargetKeyType::SEGMENT
        ],
        TargetingType::PROPERTY => [
            TargetKeyType::SEGMENT,
            TargetKeyType::USER_PROPERTY,
            TargetKeyType::HACKLE_PROPERTY,
            TargetKeyType::AB_TEST,
            TargetKeyType::FEATURE_FLAG
        ],
        TargetingType::SEGMENT => [
            TargetKeyType::USER_ID,
            TargetKeyType::USER_PROPERTY,
            TargetKeyType::HACKLE_PROPERTY
        ]
    ];

    public function supports(TargetKeyType $keyType): bool
    {
        return in_array($keyType, self::SUPPORT_TYPES[$this->getValue()]);
    }
}
