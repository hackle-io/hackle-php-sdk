<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Log\Log;

abstract class TargetAction
{
    public static function fromOrNull($data): ?TargetAction
    {
        switch ($data["type"]) {
            case "VARIATION":
                return new TargetActionVariation($data["variationId"]);
            case "BUCKET":
                return new TargetActionBucket($data["bucketId"]);
            default:
                Log::debug("Unsupported type[{$data["type"]}]. Please use the latest version of sdk.");
                return null;
        }
    }
}
