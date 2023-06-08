<?php

namespace Hackle\Internal\Model;

class TargetKey
{
    private $type;
    private $name;

    public function __construct(TargetKeyType $type, string $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    /**
     * @return TargetKeyType
     */
    public function getType(): TargetKeyType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public static function fromOrNull($data): ?TargetKey
    {
        $type = TargetKeyType::fromOrNull($data["type"]);
        if ($type === null) {
            return null;
        }
        return new TargetKey($type, $data["name"]);
    }
}
