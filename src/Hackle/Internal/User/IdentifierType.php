<?php

namespace Hackle\Internal\User;

use Hackle\Internal\Lang\Enum;

/**
 * @method static ID()
 * @method static USER()
 * @method static DEVICE()
 * @method static SESSION()
 * @method static HACKLE_DEVICE_ID()
 */
class IdentifierType extends Enum
{
    const ID = "\$id";
    const USER = "\$userId";
    const DEVICE = "\$deviceId";
    const SESSION = "\$sessionId";
    const HACKLE_DEVICE_ID = "\$hackleDeviceId";
}