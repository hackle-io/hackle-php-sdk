<?php

namespace Hackle\Tests\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Flow\Evaluator\IdentifierEvaluator;
use Hackle\Tests\Internal\Model\Models;

class IdentifierEvaluatorTest extends FlowEvaluatorTest
{
    private $sut;

    protected function setUp()
    {
        parent::setUp();
        $this->sut = new IdentifierEvaluator();
    }

    public function test_when_identifier_is_present_then_evaluate_next_flow()
    {
        // given
        $experiment = Models::experiment();
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertSame($this->evaluation, $actual);
    }

    public function test_when_identifier_not_found_then_returns_default_variation()
    {
        // given
        $experiment = Models::experiment(["identifierType" => "customId"]);
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::IDENTIFIER_NOT_FOUND(), $actual->getReason());
        self::assertEquals("A", $actual->getVariationKey());
    }
}
