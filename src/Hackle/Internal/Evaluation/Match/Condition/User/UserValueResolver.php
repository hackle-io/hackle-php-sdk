<?php

namespace Hackle\Internal\Evaluation\Match\Condition\User;

use Hackle\Internal\Model\Enums\KeyType;
use Hackle\Internal\Model\Key;
use Hackle\Internal\User\HackleUser;

final class UserValueResolver
{
    public function resolveOrNull(HackleUser $user, Key $key)
    {
        switch ($key->getType()) {
            case KeyType::USER_ID:
                return $user->getIdentifiers()[$key->getName()];
            case KeyType::USER_PROPERTY:
                return $user->getProperties()[$key->getName()];
            case KeyType::HACKLE_PROPERTY:
                return $user->getHackleProperties()[$key->getName()];
            default:
                throw new \InvalidArgumentException("Unsupported TargetKeyType [{$key->getType()}]");
        }
    }
}