<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Utils\Arrays;

class Segment
{
    private $id;
    private $key;
    private $type;
    private $targets;

    /**
     * @param int $id
     * @param string $key
     * @param SegmentType $type
     * @param Target[] $targets
     */
    public function __construct(int $id, string $key, SegmentType $type, array $targets)
    {
        $this->id = $id;
        $this->key = $key;
        $this->type = $type;
        $this->targets = $targets;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return SegmentType
     */
    public function getType(): SegmentType
    {
        return $this->type;
    }

    /**
     * @return Target[]
     */
    public function getTargets(): array
    {
        return $this->targets;
    }

    public static function fromOrNull($data): ?Segment
    {
        $type = SegmentType::fromOrNull($data["type"]);
        if ($type === null) {
            return null;
        }

        return new Segment(
            $data["id"],
            $data["key"],
            $type,
            Arrays::mapNotNull($data["targets"], function ($data) {
                return Target::fromOrNull($data, TargetingType::SEGMENT());
            })
        );
    }
}
