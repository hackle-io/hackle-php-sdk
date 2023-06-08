<?php

namespace Hackle\Internal\Model;

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
                return null;
        }
    }
}
