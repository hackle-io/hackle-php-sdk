<?php

namespace Hackle\Internal\Model;

final class Slot
{
    private $startInclusive;
    private $endExclusive;
    private $variationId;

    public function __construct(int $startInclusive, int $endExclusive, int $variationId)
    {
        $this->startInclusive = $startInclusive;
        $this->endExclusive = $endExclusive;
        $this->variationId = $variationId;
    }

    public function contains(int $slotNumber): bool
    {
        return $this->startInclusive <= $slotNumber && $slotNumber < $this->endExclusive;
    }

    /**
     * @return int
     */
    public function getVariationId(): int
    {
        return $this->variationId;
    }

    public static function from($data): Slot
    {
        return new Slot($data["startInclusive"], $data["endExclusive"], $data["variationId"]);
    }
}
