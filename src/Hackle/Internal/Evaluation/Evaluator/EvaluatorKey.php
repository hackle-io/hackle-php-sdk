<?php

namespace Hackle\Internal\Evaluation\Evaluator;

final class EvaluatorKey
{
    /** @var EvaluatorType */
    private $type;

    /** @var string */
    private $id;

    /**
     * @param EvaluatorType $type
     * @param string $id
     */
    public function __construct(EvaluatorType $type, string $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * @return EvaluatorType
     */
    public function getType(): EvaluatorType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
