<?php

namespace Hackle\Internal\Evaluation\Evaluator;

final class EvaluatorKey
{
    private $_type;
    private $_id;

    public function __construct(EvaluatorType $type, string $id)
    {
        $this->_type = $type;
        $this->_id = $id;
    }

    /**
     * @return EvaluatorType
     */
    public function getType(): EvaluatorType
    {
        return $this->_type;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->_id;
    }
}
