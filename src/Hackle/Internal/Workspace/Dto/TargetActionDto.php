<?php

namespace Hackle\Internal\Workspace\Dto;

class TargetActionDto
{
    /**@var string */
    private $_type;

    /**@var int|null */
    private $_variationId;

    /**@var int|null */
    private $_bucketId;

    public function __construct(string $_type, ?int $_variationId, ?int $_bucketId)
    {
        $this->_type = $_type;
        $this->_variationId = $_variationId;
        $this->_bucketId = $_bucketId;
    }


    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["type"], $v["variationId"] ?? null, $v["bucketId"] ?? null);
        };
    }

    public static function decode(array $v): self
    {
        $decoder = self::getDecoder();
        return $decoder($v);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * @return int|null
     */
    public function getVariationId(): ?int
    {
        return $this->_variationId;
    }

    /**
     * @return int|null
     */
    public function getBucketId(): ?int
    {
        return $this->_bucketId;
    }
}
