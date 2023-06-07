<?php

namespace Hackle\Internal\Evaluation\Match\Condition\User;

use Hackle\Internal\Model\TargetKey;
use Hackle\Internal\Model\TargetKeyType;
use Hackle\Internal\User\InternalHackleUser;

class UserValueResolver
{
    /**
     * @param InternalHackleUser $user
     * @param TargetKey $key
     * @return mixed|null
     */
    public function resolveOrNull(InternalHackleUser $user, TargetKey $key)
    {
        switch ($key->getType()) {
            case TargetKeyType::USER_ID:
                return $user->getIdentifiers()[$key->getName()] ?? null;
            case TargetKeyType::USER_PROPERTY:
                return $user->getProperties()[$key->getName()] ?? null;
            case TargetKeyType::HACKLE_PROPERTY:
                return $user->getHackleProperties()[$key->getName()] ?? null;
            default:
                throw new \InvalidArgumentException("Unsupported TargetKeyType [{$key->getType()}]");
        }
    }
}
