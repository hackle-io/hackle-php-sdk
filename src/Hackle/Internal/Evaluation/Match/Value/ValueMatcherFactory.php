<?php

namespace Hackle\Internal\Evaluation\Match\Value;

use Hackle\Internal\Model\ValueType;

final class ValueMatcherFactory
{
    private $stringMatcher;
    private $numberMatcher;
    private $boolMatcher;
    private $versionMatcher;

    public function __construct()
    {
        $this->stringMatcher = new StringMatcher();
        $this->numberMatcher = new NumberMatcher();
        $this->boolMatcher = new BoolMatcher();
        $this->versionMatcher = new VersionMatcher();
    }

    public function getMatcher(ValueType $valueType): ValueMatcher
    {
        switch ($valueType) {
            case ValueType::JSON:
            case ValueType::STRING:
                return $this->stringMatcher;
            case ValueType::NUMBER:
                return $this->numberMatcher;
            case ValueType::BOOLEAN:
                return $this->boolMatcher;
            case ValueType::VERSION:
                return $this->versionMatcher;
            default:
                throw new \InvalidArgumentException("Unsupported valueType [$valueType]");
        }
    }
}
