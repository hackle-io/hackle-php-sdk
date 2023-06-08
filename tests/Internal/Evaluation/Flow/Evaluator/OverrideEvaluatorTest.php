<?php

namespace Hackle\Tests\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Flow\Evaluator\OverrideEvaluator;
use Hackle\Internal\Evaluation\Target\OverrideResolver;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Tests\Internal\Model\Models;

class OverrideEvaluatorTest extends FlowEvaluatorTest
{
    private $overrideResolver;
    private $sut;

    public function setUp()
    {
        parent::setUp();
        $this->overrideResolver = $this->createMock(OverrideResolver::class);
        $this->sut = new OverrideEvaluator($this->overrideResolver);
    }

    public function test_AB_TEST_overridden()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::AB_TEST()]);
        $variation = $experiment->getVariations()[0];
        $this->overrideResolver->method("resolveOrNull")->willReturn($variation);

        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::OVERRIDDEN(), $actual->getReason());
        self::assertEquals($variation->getId(), $actual->getVariationId());
    }

    public function test_FEATURE_FLAG_overridden()
    {
        // given
        $experiment = Models::experiment(["type" => ExperimentType::FEATURE_FLAG()]);
        $variation = $experiment->getVariations()[0];
        $this->overrideResolver->method("resolveOrNull")->willReturn($variation);

        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::INDIVIDUAL_TARGET_MATCH(), $actual->getReason());
        self::assertEquals($variation->getId(), $actual->getVariationId());
    }

    public function test_when_not_overridden_then_evaluate_next_flow()
    {
        // given
        $this->overrideResolver->method("resolveOrNull")->willReturn(null);

        // when
        $actual = $this->sut->evaluate(Models::experimentRequest(), $this->context, $this->nextFlow);

        // then
        self::assertSame($this->evaluation, $actual);
    }
}
