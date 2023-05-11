<?php

namespace Hackle\Internal\Model\Enums;

use Hackle\Common\Enum;

class ExperimentStatus extends Enum
{
    const DRAFT = "DRAFT";

    const RUNNING = "RUNNING";

    const PAUSED = "PAUSED";

    const COMPLETED = "COMPLETED";
}
