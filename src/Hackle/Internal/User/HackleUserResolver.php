<?php

namespace Hackle\Internal\User;

use Hackle\Common\User;

class HackleUserResolver
{
    public function resolveOrNull(User $user): ?HackleUser
    {
        $hackleUser = HackleUser::builder()->identifiers($user->getIdentifiers())->identifier(new IdentifierType(IdentifierType::ID), $user->getId())->identifier(new IdentifierType(IdentifierType::USER), $user->getUserId())->identifier(new IdentifierType(IdentifierType::DEVICE), $user->getDeviceId())->properties($user->getProperties())->build();
        if (empty($hackleUser->getIdentifiers())) {
            return null;
        }
        return $hackleUser;
    }
}
