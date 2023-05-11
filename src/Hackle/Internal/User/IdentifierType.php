<?php

namespace Hackle\Internal\User;

use Hackle\Common\Enum;

class IdentifierType extends Enum
{
    const ID = "\$id";

    const USER = "\$userId";

    const DEVICE = "\$deviceId";

    const SESSION = "\$sessionId";

    const HACKLE_DEVICE_ID = "\$hackleDeviceId";
}