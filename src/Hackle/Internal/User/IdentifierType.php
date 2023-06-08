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
    public const ID = "\$id";
    public const USER = "\$userId";
    public const DEVICE = "\$deviceId";
    public const SESSION = "\$sessionId";
    public const HACKLE_DEVICE_ID = "\$hackleDeviceId";
}