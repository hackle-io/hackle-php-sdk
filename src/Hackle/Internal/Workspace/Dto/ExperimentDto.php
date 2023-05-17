<?php

namespace Hackle\Internal\Workspace\Dto;

class ExperimentDto
{
    /**@var int */
    private $_id;

    /**@var int */
    private $_key;

    /**@var string */
    private $_status;

    /**@var int */
    private $_version;

    /**@var VariationDto[] */
    private $_variations;

    /**@var ExecutionDto */
    private $_execution;

    /**@var ?int */
    private $_winnerVariationId;

    /**@var string */
    private $_identifierType;

    /**@var ?int */
    private $_containerId;

    /**
     * @param int $_id
     * @param int $_key
     * @param string $_status
     * @param int $_version
     * @param VariationDto[] $_variations
     * @param ExecutionDto $_execution
     * @param int|null $_winnerVariationId
     * @param string $_identifierType
     * @param int|null $_containerId
     */
    public function __construct(int $_id, int $_key, string $_status, int $_version, array $_variations, ExecutionDto $_execution, ?int $_winnerVariationId, string $_identifierType, ?int $_containerId)
    {
        $this->_id = $_id;
        $this->_key = $_key;
        $this->_status = $_status;
        $this->_version = $_version;
        $this->_variations = $_variations;
        $this->_execution = $_execution;
        $this->_winnerVariationId = $_winnerVariationId;
        $this->_identifierType = $_identifierType;
        $this->_containerId = $_containerId;
    }

    public static function getDecoder(): \Closure
    {
        return function (array $v) {
            return new self($v["id"], $v["key"], $v["status"], $v["version"], array_map(VariationDto::getDecoder(), $v["variations"]), call_user_func(ExecutionDto::getDecoder(), $v["execution"]), $v["winnerVariationId"] ?? null, $v["identifierType"], $v["containerId"] ?? null);
        };
    }

    public static function decode(array $v): self
    {
        $decoder = self::getDecoder();
        return $decoder($v);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * @return int
     */
    public function getKey(): int
    {
        return $this->_key;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->_status;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->_version;
    }

    /**
     * @return VariationDto[]
     */
    public function getVariations(): array
    {
        return $this->_variations;
    }

    /**
     * @return ExecutionDto
     */
    public function getExecution(): ExecutionDto
    {
        return $this->_execution;
    }

    /**
     * @return int|null
     */
    public function getWinnerVariationId(): ?int
    {
        return $this->_winnerVariationId;
    }

    /**
     * @return string
     */
    public function getIdentifierType(): string
    {
        return $this->_identifierType;
    }

    /**
     * @return int|null
     */
    public function getContainerId(): ?int
    {
        return $this->_containerId;
    }
}
