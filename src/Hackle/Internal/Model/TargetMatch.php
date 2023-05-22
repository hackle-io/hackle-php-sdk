<?php

namespace Hackle\Internal\Model;


class TargetMatch
{
    private $type;
    private $operator;
    private $valueType;
    private $values;

    /**
     * @template T
     *
     * @param MatchType $type
     * @param MatchOperator $operator
     * @param ValueType $valueType
     * @param T[] $values
     */
    public function __construct(MatchType $type, MatchOperator $operator, ValueType $valueType, array $values)
    {
        $this->type = $type;
        $this->operator = $operator;
        $this->valueType = $valueType;
        $this->values = $values;
    }

    /**
     * @return MatchType
     */
    public function getType(): MatchType
    {
        return $this->type;
    }

    /**
     * @return MatchOperator
     */
    public function getOperator(): MatchOperator
    {
        return $this->operator;
    }

    /**
     * @return ValueType
     */
    public function getValueType(): ValueType
    {
        return $this->valueType;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public static function fromOrNull($data): ?TargetMatch
    {
        $type = MatchType::fromOrNull($data["type"]);
        if ($type === null) {
            return null;
        }

        $operator = MatchOperator::fromOrNull($data["operator"]);
        if ($operator === null) {
            return null;
        }

        $valueType = ValueType::fromOrNull($data["valueType"]);
        if ($valueType === null) {
            return null;
        }

        return new TargetMatch($type, $operator, $valueType, $data["values"]);
    }
}
