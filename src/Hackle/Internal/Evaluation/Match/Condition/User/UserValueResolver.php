<?php

namespace Hackle\Internal\Evaluation\Match\Condition\User;

use Hackle\Internal\Model\TargetKey;
use Hackle\Internal\Model\TargetKeyType;
use Hackle\Internal\User\HackleUser;

final class UserValueResolver
{
    public function resolveOrNull(HackleUser $user, TargetKey $key)
    {
        switch ($key->getType()) {
            case TargetKeyType::USER_ID:
                return $user->getIdentifiers()[$key->getName()];
            case TargetKeyType::USER_PROPERTY:
                return $user->getProperties()[$key->getName()];
            case TargetKeyType::HACKLE_PROPERTY:
                return $user->getHackleProperties()[$key->getName()];
            default:
                throw new \InvalidArgumentException("Unsupported TargetKeyType [{$key->getType()}]");
        }
    }
}