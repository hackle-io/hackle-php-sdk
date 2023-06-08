<?php

namespace Hackle\Tests\Internal\Evaluation\Evaluator;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class EvaluatorContextTest extends TestCase
{

    public function test__stack()
    {
        $context = new EvaluatorContext();
        self::assertEquals(0, count($context->getStack()));

        $request1 = Models::experimentRequest(Models::experiment(["id" => 1]));
        $context->push($request1);
        $stack1 = $context->getStack();
        self::assertEquals(1, count($stack1));

        $request2 = Models::experimentRequest(Models::experiment(["id" => 2]));
        $context->push($request2);
        $stack2 = $context->getStack();
        self::assertEquals(2, count($stack2));

        $context->pop();
        self::assertEquals(1, count($context->getStack()));

        $context->pop();
        self::assertEquals(0, count($context->getStack()));

        self::assertEquals(1, count($stack1));
        self::assertEquals(2, count($stack2));
    }
}
