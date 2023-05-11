<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Model\Enums\MatchType;
use Hackle\Internal\Model\Enums\Operator;
use Hackle\Internal\Model\Enums\ValueType;

class Match
{
    /** @var MatchType */
    private $_type;

    /** @var Operator */
    private $_operator;

    /** @var ValueType */
    private $_valueType;

    /** @var array */
    private $_values;

    public function __construct(MatchType $_type, Operator $_operator, ValueType $_valueType, array $_values)
    {
        $this->_type = $_type;
        $this->_operator = $_operator;
        $this->_valueType = $_valueType;
        $this->_values = $_values;
    }

    /**
     * @return MatchType
     */
    public function getType(): MatchType
    {
        return $this->_type;
    }

    /**
     * @param MatchType $type
     */
    public function setType(MatchType $type): void
    {
        $this->_type = $type;
    }

    /**
     * @return Operator
     */
    public function getOperator(): Operator
    {
        return $this->_operator;
    }

    /**
     * @param Operator $operator
     */
    public function setOperator(Operator $operator): void
    {
        $this->_operator = $operator;
    }

    /**
     * @return ValueType
     */
    public function getValueType(): ValueType
    {
        return $this->_valueType;
    }

    /**
     * @param ValueType $valueType
     */
    public function setValueType(ValueType $valueType): void
    {
        $this->_valueType = $valueType;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->_values;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values): void
    {
        $this->_values = $values;
    }
}
