<?php

namespace Hackle\Internal\Evaluation\Match\Value;

use Hackle\Internal\Model\Enums\ValueType;

final class ValueMatcherFactory
{
    private $_stringMatcher;
    private $_numberMatcher;
    private $_boolMatcher;
    private $_versionMatcher;

    public function __construct()
    {
        $this->_stringMatcher = new StringMatcher();
        $this->_numberMatcher = new NumberMatcher();
        $this->_boolMatcher = new BoolMatcher();
        $this->_versionMatcher = new VersionMatcher();
    }


    public function getMatcher(ValueType $valueType): ValueMatcher
    {
        switch ($valueType) {
            case ValueType::JSON:
            case ValueType::STRING:
                return $this->_stringMatcher;
            case ValueType::NUMBER:
                return $this->_numberMatcher;
            case ValueType::BOOLEAN:
                return $this->_boolMatcher;
            case ValueType::VERSION:
                return $this->_versionMatcher;
            default:
                throw new \InvalidArgumentException("Unsupported valueType [$valueType]");
        }
    }
}