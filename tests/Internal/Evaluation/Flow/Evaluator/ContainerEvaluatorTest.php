<?php

namespace Hackle\Tests\Internal\Evaluation\Flow\Evaluator;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Container\ContainerResolver;
use Hackle\Internal\Evaluation\Flow\Evaluator\ContainerEvaluator;
use Hackle\Internal\Model\Container;
use Hackle\Internal\Workspace\Workspace;
use Hackle\Tests\Internal\Model\Models;

class ContainerEvaluatorTest extends FlowEvaluatorTest
{
    private $containerResolver;
    private $sut;

    public function setUp()
    {
        parent::setUp();
        $this->containerResolver = $this->createMock(ContainerResolver::class);
        $this->sut = new ContainerEvaluator($this->containerResolver);
    }

    public function test_when_not_mutual_exclusion_experiment_then_evaluate_next_flow()
    {
        // given
        $experiment = Models::experiment();
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertSame($this->evaluation, $actual);
    }

    public function test_when_cannot_found_container_then_throws_exception()
    {
        // given
        $workspace = $this->createMock(Workspace::class);
        $experiment = Models::experiment(["id" => 42, "containerId" => 320]);
        $request = Models::experimentRequest($experiment, $workspace);

        $workspace->method("getContainerOrNull")->willReturn(null);

        // when
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Container[320]");
        $this->sut->evaluate($request, $this->context, $this->nextFlow);
    }

    public function test_when_user_in_container_group_then_evaluate_next_flow()
    {
        // given
        $workspace = $this->createMock(Workspace::class);
        $experiment = Models::experiment(["id" => 42, "containerId" => 320]);
        $request = Models::experimentRequest($experiment, $workspace);

        $workspace->method("getContainerOrNull")->willReturn($this->createMock(Container::class));
        $this->containerResolver->method("isUserInContainerGroup")->willReturn(true);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertSame($this->evaluation, $actual);
    }

    public function test_when_mutual_exclusion_experiment_but_not_in_container_group_then_return_default_variation()
    {
        // given
        $workspace = $this->createMock(Workspace::class);
        $experiment = Models::experiment(["id" => 42, "containerId" => 320]);
        $request = Models::experimentRequest($experiment, $workspace);

        $workspace->method("getContainerOrNull")->willReturn($this->createMock(Container::class));
        $this->containerResolver->method("isUserInContainerGroup")->willReturn(false);

        // when
        $actual = $this->sut->evaluate($request, $this->context, $this->nextFlow);

        // then
        self::assertEquals(DecisionReason::NOT_IN_MUTUAL_EXCLUSION_EXPERIMENT(), $actual->getReason());
        self::assertEquals("A", $actual->getVariationKey());
    }
}
