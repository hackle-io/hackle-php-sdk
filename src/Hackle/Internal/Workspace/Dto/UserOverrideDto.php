<?php

namespace Hackle\Internal\Workspace\Dto;

class UserOverrideDto
{
    /** @var string */
    private $_userId;

    /** @var int */
    private $_variationId;

    public function __construct(string $_userId, int $_variationId)
    {
        $this->_userId = $_userId;
        $this->_variationId = $_variationId;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["userId"], $v["variationId"]);
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
    public function getUserId(): string
    {
        return $this->_userId;
    }

    /**
     * @return int
     */
    public function getVariationId(): int
    {
        return $this->_variationId;
    }
}
