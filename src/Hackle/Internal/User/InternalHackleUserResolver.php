<?php

namespace Hackle\Internal\User;

use Hackle\Common\HackleUser;

class InternalHackleUserResolver
{
    public function resolveOrNull(HackleUser $user): ?InternalHackleUser
    {
        $hackleUser = InternalHackleUser::builder()
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
