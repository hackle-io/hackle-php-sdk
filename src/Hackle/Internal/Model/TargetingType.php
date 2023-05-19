<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Model\Enums\KeyType;
use ReflectionException;

class TargetingType
{
    public const IDENTIFIER = "IDENTIFIER";

    public const PROPERTY = "PROPERTY";

    public const SEGMENT = "SEGMENT";

    private static $_identifierSupportKeyTypes = array(KeyType::SEGMENT);

    private static $_propertySupportKeyTypes = array(KeyType::SEGMENT, KeyType::USER_PROPERTY, KeyType::HACKLE_PROPERTY, KeyType::AB_TEST, KeyType::FEATURE_FLAG);

    private static $_segmentSupportKeyTypes = array(KeyType::USER_ID, KeyType::USER_PROPERTY, KeyType::HACKLE_PROPERTY);

    public static function supports(string $targetingType, KeyType $keyType): bool
    {
        try {
            switch ($targetingType) {
                case self::IDENTIFIER:
                    return in_array($keyType->getKey(), self::$_identifierSupportKeyTypes);
                case self::PROPERTY:
                    return in_array($keyType->getKey(), self::$_propertySupportKeyTypes);
                case self::SEGMENT:
                    return in_array($keyType->getKey(), self::$_segmentSupportKeyTypes);
            }
            return false;
        } catch (ReflectionException $e) {
            return false;
        }
    }
}
