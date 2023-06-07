<?php

namespace Hackle\Tests\Internal\Evaluation\Evaluator\Experiment;

use Hackle\Common\DecisionReason;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Evaluator\EvaluatorEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentEvaluation;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Model\ParameterConfiguration;
use Hackle\Internal\User\InternalHackleUser;
use Hackle\Internal\Workspace\Workspace;
use Hackle\Tests\Internal\Model\Models;
use Mockery;
use PHPUnit\Framework\TestCase;

class ExperimentEvaluationTest extends TestCase
{

    public function test_create_by_variation()
    {
        $experiment = Models::experiment([
            "id" => 42,
            "key" => 50,
            "variations" => [
                Models::variation(320, "A", false, 99),
                Models::variation(321, "B", false, 100),
            ]
        ]);
        $variation = $experiment->getVariationOrNullById(321);

        $config = Mockery::mock(ParameterConfiguration::class);
        $workspace = Mockery::mock(Workspace::class);
        $workspace->allows()->getParameterConfigurationOrNull(100)->andReturn($config);

        $user = InternalHackleUser::builder()->build();
        $request = ExperimentRequest::of($workspace, $user, $experiment, "H");

        $context = new EvaluatorContext();
        $context->add(Mockery::mock(EvaluatorEvaluation::class));

        $evaluation = ExperimentEvaluation::of(
            $request,
            $context,
            $variation,
            DecisionReason::TRAFFIC_ALLOCATED()
        );

        self::assertEquals(DecisionReason::TRAFFIC_ALLOCATED(), $evaluation->getReason());
        self::assertEquals(1, count($evaluation->getTargetEvaluations()));
        self::assertEquals($experiment, $evaluation->getExperiment());
        self::assertEquals(321, $evaluation->getVariationId());
        self::assertEquals("B", $evaluation->getVariationKey());
        self::assertEquals($config, $evaluation->getConfig());
    }

    public function test_create_by_variation_config_is_null()
    {
        $experiment = Models::experiment([
            "id" => 42,
            "key" => 50,
            "variations" => [
                Models::variation(320, "A"),
                Models::variation(321, "B"),
            ]
        ]);
        $variation = $experiment->getVariationOrNullById(321);

        $workspace = Mockery::mock(Workspace::class);

        $user = InternalHackleUser::builder()->build();
        $request = ExperimentRequest::of($workspace, $user, $experiment, "H");

        $context = new EvaluatorContext();
        $context->add(Mockery::mock(EvaluatorEvaluation::class));

        $evaluation = ExperimentEvaluation::of(
            $request,
            $context,
            $variation,
            DecisionReason::TRAFFIC_ALLOCATED()
        );

        self::assertEquals(DecisionReason::TRAFFIC_ALLOCATED(), $evaluation->getReason());
        self::assertEquals(1, count($evaluation->getTargetEvaluations()));
        self::assertEquals($experiment, $evaluation->getExperiment());
        self::assertEquals(321, $evaluation->getVariationId());
        self::assertEquals("B", $evaluation->getVariationKey());
        self::assertNull($evaluation->getConfig());
    }

    public function test_create_by_variation_config_not_found()
    {
        $experiment = Models::experiment([
            "id" => 42,
            "key" => 50,
            "variations" => [
                Models::variation(320, "A"),
                Models::variation(321, "B", false, 100),
            ]
        ]);
        $variation = $experiment->getVariationOrNullById(321);

        $workspace = Mockery::mock(Workspace::class);
        $workspace->allows("getParameterConfigurationOrNull")->andReturn(null);

        $user = InternalHackleUser::builder()->build();
        $request = ExperimentRequest::of($workspace, $user, $experiment, "H");

        $context = new EvaluatorContext();
        $context->add(Mockery::mock(EvaluatorEvaluation::class));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ParameterConfiguration[100]");

        ExperimentEvaluation::of(
            $request,
            $context,
            $variation,
            DecisionReason::TRAFFIC_ALLOCATED()
        );
    }

    public function test_create_by_default()
    {
        $experiment = Models::experiment([
            "id" => 42,
            "key" => 50,
            "variations" => [
                Models::variation(320, "A"),
                Models::variation(321, "B"),
            ]
        ]);
        $workspace = Mockery::mock(Workspace::class);

        $user = InternalHackleUser::builder()->build();
        $request = ExperimentRequest::of($workspace, $user, $experiment, "A");

        $evaluation = ExperimentEvaluation::ofDefault(
            $request,
            new EvaluatorContext(),
            DecisionReason::TRAFFIC_NOT_ALLOCATED()
        );

        self::assertEquals(DecisionReason::TRAFFIC_NOT_ALLOCATED(), $evaluation->getReason());
        self::assertEquals(0, count($evaluation->getTargetEvaluations()));
        self::assertEquals($experiment, $evaluation->getExperiment());
        self::assertEquals(320, $evaluation->getVariationId());
        self::assertEquals("A", $evaluation->getVariationKey());
        self::assertNull($evaluation->getConfig());
    }

    public function test_create_by_default_null()
    {
        $experiment = Models::experiment([
            "id" => 42,
            "key" => 50,
            "variations" => [
                Models::variation(320, "A"),
                Models::variation(321, "B"),
            ]
        ]);
        $workspace = Mockery::mock(Workspace::class);

        $user = InternalHackleUser::builder()->build();
        $request = ExperimentRequest::of($workspace, $user, $experiment, "C");

        $evaluation = ExperimentEvaluation::ofDefault(
            $request,
            new EvaluatorContext(),
            DecisionReason::TRAFFIC_NOT_ALLOCATED()
        );

        self::assertEquals(DecisionReason::TRAFFIC_NOT_ALLOCATED(), $evaluation->getReason());
        self::assertEquals(0, count($evaluation->getTargetEvaluations()));
        self::assertEquals($experiment, $evaluation->getExperiment());
        self::assertEquals(null, $evaluation->getVariationId());
        self::assertEquals("C", $evaluation->getVariationKey());
        self::assertNull($evaluation->getConfig());
    }
}
