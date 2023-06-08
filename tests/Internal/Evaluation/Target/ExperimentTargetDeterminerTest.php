<?php

namespace Hackle\Tests\Internal\Evaluation\Target;

use Hackle\Internal\Evaluation\Evaluator\EvaluatorContext;
use Hackle\Internal\Evaluation\Match\TargetMatcher;
use Hackle\Internal\Evaluation\Target\ExperimentTargetDeterminer;
use Hackle\Internal\Model\Target;
use Hackle\Tests\Internal\Model\Models;
use PHPUnit\Framework\TestCase;

class ExperimentTargetDeterminerTest extends TestCase
{
    private $targetMatcher;
    private $sut;
    private $callCount;

    protected function setUp()
    {
        $this->targetMatcher = $this->createMock(TargetMatcher::class);
        $this->sut = new ExperimentTargetDeterminer($this->targetMatcher);
        $this->callCount = 0;
    }

    public function test_when_audiences_is_empty_then_returns_true()
    {
        // given
        $experiment = Models::experiment();
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->isUserInExperimentTarget($request, new EvaluatorContext());

        // then
        self::assertTrue($actual);
    }

    public function test_when_any_of_audiences_match_then_return_true()
    {
        // given
        $audiences = $this->createAudiences(false, false, false, true, false);
        $experiment = Models::experiment(["targetAudiences" => $audiences]);
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->isUserInExperimentTarget($request, new EvaluatorContext());

        // then
        self::assertTrue($actual);
        self::assertEquals(4, $this->callCount);
    }

    public function test_when_all_audiences_do_not_match_then_returns_false()
    {
        // given
        $audiences = $this->createAudiences(false, false, false, false, false);
        $experiment = Models::experiment(["targetAudiences" => $audiences]);
        $request = Models::experimentRequest($experiment);

        // when
        $actual = $this->sut->isUserInExperimentTarget($request, new EvaluatorContext());

        // then
        self::assertFalse($actual);
        self::assertEquals(5, $this->callCount);
    }

    private function createAudiences(bool ...$isMatches): array
    {
        $this->targetMatcher->method("matches")->willReturnCallback(function () use ($isMatches) {
            return $isMatches[$this->callCount++];
        });
        return array_map(function () {
            return $this->createMock(Target::class);
        }, $isMatches);
    }
}
