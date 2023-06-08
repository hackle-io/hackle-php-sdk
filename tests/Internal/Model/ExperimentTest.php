<?php

namespace Hackle\Tests\Internal\Model;

use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Internal\Model\Variation;
use PHPUnit\Framework\TestCase;

class ExperimentTest extends TestCase
{
    public function testGetVariationById()
    {
        $experiment = Models::experiment(["status" => ExperimentStatus::DRAFT()]);
        self::assertEquals(
            new Variation(1, "A", false, null),
            $experiment->getVariationOrNullById(1)
        );
        self::assertEquals(
            new Variation(2, "B", false, null),
            $experiment->getVariationOrNullById(2)
        );
        self::assertNull($experiment->getVariationOrNullById(3));
    }

    public function testGetVariationByKey()
    {
        $experiment = Models::experiment(["status" => ExperimentStatus::DRAFT()]);
        self::assertEquals(
            new Variation(1, "A", false, null),
            $experiment->getVariationOrNullByKey("A")
        );
        self::assertEquals(
            new Variation(2, "B", false, null),
            $experiment->getVariationOrNullByKey("B")
        );
        self::assertNull($experiment->getVariationOrNullByKey("C"));
    }

    public function testGetWinnerVariation()
    {
        $experiment = Models::experiment([
            "id" => 42,
            "status" => ExperimentStatus::COMPLETED(),
            "variations" => array(
                Models::variation(41, "A"),
                Models::variation(42, "B"),
            ),
            "winnerVariationId" => 42
        ]);

        self::assertEquals($experiment->getWinnerVariation(), new Variation(42, "B", false, null));
    }

    public function testGetWinnerVariationFail()
    {
        $experiment = Models::experiment([
            "id" => 42,
            "status" => ExperimentStatus::COMPLETED(),
            "variations" => array(
                Models::variation(41, "A"),
                Models::variation(42, "B"),
            )
        ]);

        self::assertNull($experiment->getWinnerVariation());
    }

    public function testStatus()
    {
        self::assertEquals(ExperimentStatus::DRAFT(), ExperimentStatus::fromExecutionStatusOrNull("READY"));
        self::assertEquals(ExperimentStatus::RUNNING(), ExperimentStatus::fromExecutionStatusOrNull("RUNNING"));
        self::assertEquals(ExperimentStatus::PAUSED(), ExperimentStatus::fromExecutionStatusOrNull("PAUSED"));
        self::assertEquals(ExperimentStatus::COMPLETED(), ExperimentStatus::fromExecutionStatusOrNull("STOPPED"));
    }
}
