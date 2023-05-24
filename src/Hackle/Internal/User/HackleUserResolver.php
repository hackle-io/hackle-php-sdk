<?php

namespace Hackle\Internal\User;

use Hackle\Common\User;

class HackleUserResolver
{
    public static function resolveOrNull(User $user): ?HackleUser
    {
        $hackleUser = HackleUser::builder()
            ->identifiers($user->getIdentifiers())
            ->identifier(IdentifierType::ID(), $user->getId())
            ->identifier(IdentifierType::USER(), $user->getUserId())
            ->identifier(IdentifierType::DEVICE(), $user->getDeviceId())
            ->properties($user->getProperties())
            ->build();
        if (empty($hackleUser->getIdentifiers())) {
            return null;
        }
        return $hackleUser;
    }
}
