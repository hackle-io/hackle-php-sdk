<?php

namespace Hackle\Tests\Internal\Workspace;

use Hackle\Internal\Model\Bucket;
use Hackle\Internal\Model\EventType;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\Model\ExperimentStatus;
use Hackle\Internal\Model\ExperimentType;
use Hackle\Internal\Model\MatchOperator;
use Hackle\Internal\Model\MatchType;
use Hackle\Internal\Model\Target;
use Hackle\Internal\Model\TargetAction;
use Hackle\Internal\Model\TargetActionBucket;
use Hackle\Internal\Model\TargetActionVariation;
use Hackle\Internal\Model\TargetCondition;
use Hackle\Internal\Model\TargetKey;
use Hackle\Internal\Model\TargetKeyType;
use Hackle\Internal\Model\TargetMatch;
use Hackle\Internal\Model\TargetRule;
use Hackle\Internal\Model\ValueType;
use Hackle\Internal\Model\Variation;
use PHPUnit\Framework\TestCase;

class DefaultWorkspaceTest extends TestCase
{
    public function testWorkspaceConfig()
    {
        $workspaceFetcher = new ResourcesWorkspaceFetcher(
            __DIR__ . "/../../Resources/workspace_config.json"
        );

        $workspace = $workspaceFetcher->fetch();

        //experiment key = 4
        $experiment4 = $workspace->getExperimentOrNull(4);
        self::assertNull($experiment4);

        //experiment key = 5
        $experiment5 = $workspace->getExperimentOrNull(5);
        self::assertNotNull($experiment5);
        $this->identifier($experiment5, 4318, 5, ExperimentType::AB_TEST());
        $this->hasVariations(
            $experiment5,
            new Variation(13378, "A", false, 1),
            new Variation(13379, "B", false, null)
        );
        $this->hasOverrides($experiment5, []);
        self::assertEquals(2, $experiment5->getVersion());
        self::assertEquals(3, $experiment5->getExecutionVersion());

        //experiment key = 6
        $experiment6 = $workspace->getExperimentOrNull(6);
        self::assertNotNull($experiment6);
        $this->identifier($experiment6, 4319, 6, ExperimentType::AB_TEST());
        $this->hasVariations(
            $experiment6,
            new Variation(13380, "A", false, null),
            new Variation(13381, "B", false, null)
        );
        $this->hasOverrides(
            $experiment6,
            array("user_1" => 13380, "user_2" => 13381)
        );

        self::assertEquals(ExperimentStatus::DRAFT(), $workspace->getExperimentOrNull(6)->getStatus());

        //experiment key = 7
        $experiment7 = $workspace->getExperimentOrNull(7);
        self::assertNotNull($experiment7);
        $this->identifier($experiment7, 4320, 7, ExperimentType::AB_TEST());
        $this->hasVariations(
            $experiment7,
            new Variation(13382, "A", false, null),
            new Variation(13383, "B", false, null),
            new Variation(13384, "C", false, null)
        );
        $this->hasOverrides($experiment7, []);
        self::assertEquals(ExperimentStatus::RUNNING(), $experiment7->getStatus());
        self::assertCount(3, $experiment7->getTargetAudiences());
        self::assertEquals(
            new Target(array(
                new TargetCondition(
                    new TargetKey(TargetKeyType::USER_PROPERTY(), "age"),
                    new TargetMatch(MatchType::MATCH(), MatchOperator::GTE(), ValueType::NUMBER(), array(20.0))
                ),
                new TargetCondition(
                    new TargetKey(TargetKeyType::USER_PROPERTY(), "age"),
                    new TargetMatch(MatchType::MATCH(), MatchOperator::LT(), ValueType::NUMBER(), array(30.0))
                )
            )),
            $experiment7->getTargetAudiences()[0]
        );
        self::assertEquals(
            new Target(array(
                new TargetCondition(
                    new TargetKey(TargetKeyType::USER_PROPERTY(), "platform"),
                    new TargetMatch(
                        MatchType::MATCH(),
                        MatchOperator::IN(),
                        ValueType::STRING(),
                        array("android", "ios")
                    )
                )
            )),
            $experiment7->getTargetAudiences()[1]
        );
        self::assertEquals(
            new Target(array(
                new TargetCondition(
                    new TargetKey(TargetKeyType::USER_PROPERTY(), "membership"),
                    new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::BOOLEAN(), array(true))
                )
            )),
            $experiment7->getTargetAudiences()[2]
        );
        self::assertInstanceOf(TargetActionBucket::class, $experiment7->getDefaultRule());
        self::isEqualTargetAction(6100, $experiment7->getDefaultRule());

        //experiment key = 8
        $experiment8 = $workspace->getExperimentOrNull(8);
        self::assertNotNull($experiment8);
        $this->identifier($experiment8, 4321, 8, ExperimentType::AB_TEST());
        $this->hasVariations(
            $experiment8,
            new Variation(13385, "A", false, null),
            new Variation(13386, "B", false, null)
        );
        $this->hasOverrides($experiment8, []);
        self::assertEquals(ExperimentStatus::RUNNING(), $experiment8->getStatus());
        self::assertEquals(
            new Target(array(
                new TargetCondition(
                    new TargetKey(TargetKeyType::USER_PROPERTY(), "address"),
                    new TargetMatch(MatchType::MATCH(), MatchOperator::CONTAINS(), ValueType::STRING(), array("seoul"))
                )
            )),
            $experiment8->getTargetAudiences()[0]
        );
        self::assertEquals(
            new Target(array(
                new TargetCondition(
                    new TargetKey(TargetKeyType::USER_PROPERTY(), "name"),
                    new TargetMatch(MatchType::MATCH(), MatchOperator::STARTS_WITH(), ValueType::STRING(), array("kim"))
                )
            )),
            $experiment8->getTargetAudiences()[1]
        );
        self::assertEquals(
            new Target(array(
                new TargetCondition(
                    new TargetKey(TargetKeyType::USER_PROPERTY(), "message"),
                    new TargetMatch(MatchType::NOT_MATCH(), MatchOperator::ENDS_WITH(), ValueType::STRING(), array("!"))
                )
            )),
            $experiment8->getTargetAudiences()[2]
        );
        self::assertEquals(
            new Target(array(
                new TargetCondition(
                    new TargetKey(TargetKeyType::USER_PROPERTY(), "point"),
                    new TargetMatch(MatchType::MATCH(), MatchOperator::GT(), ValueType::NUMBER(), array(100.0))
                ),
                new TargetCondition(
                    new TargetKey(TargetKeyType::USER_PROPERTY(), "point"),
                    new TargetMatch(MatchType::MATCH(), MatchOperator::LTE(), ValueType::NUMBER(), array(200.0))
                )
            )),
            $experiment8->getTargetAudiences()[3]
        );
        self::assertInstanceOf(TargetActionBucket::class, $experiment8->getDefaultRule());
        self::isEqualTargetAction(6103, $experiment8->getDefaultRule());

        //experiment key = 9
        $experiment9 = $workspace->getExperimentOrNull(9);
        self::assertNotNull($experiment9);
        $this->identifier($experiment9, 4322, 9, ExperimentType::AB_TEST());
        $this->hasVariations(
            $experiment9,
            new Variation(13387, "A", false, null),
            new Variation(13388, "B", false, null),
            new Variation(13389, "C", true, null)
        );
        $this->hasOverrides($experiment9, []);
        self::assertEquals(ExperimentStatus::RUNNING(), $experiment9->getStatus());
        self::assertInstanceOf(TargetActionBucket::class, $experiment9->getDefaultRule());
        self::isEqualTargetAction(6106, $experiment9->getDefaultRule());

        //experiment key = 10
        $experiment10 = $workspace->getExperimentOrNull(10);
        self::assertNotNull($experiment10);
        $this->identifier($experiment10, 4323, 10, ExperimentType::AB_TEST());
        $this->hasVariations(
            $experiment10,
            new Variation(13390, "A", false, null),
            new Variation(13391, "B", false, null)
        );
        $this->hasOverrides($experiment10, []);
        self::assertEquals(ExperimentStatus::PAUSED(), $experiment10->getStatus());

        //experiment key = 11
        $experiment11 = $workspace->getExperimentOrNull(11);
        self::assertNotNull($experiment11);
        $this->identifier($experiment11, 4324, 11, ExperimentType::AB_TEST());
        $this->hasVariations(
            $experiment11,
            new Variation(13392, "A", false, null),
            new Variation(13393, "B", false, null),
            new Variation(13394, "C", false, null),
            new Variation(13395, "D", false, null)
        );
        $this->hasOverrides($experiment11, []);
        self::assertEquals(ExperimentStatus::COMPLETED(), $experiment11->getStatus());
        self::assertEquals(
            new Variation(13395, "D", false, null),
            $experiment11->getWinnerVariation()
        );

        //feature flag key = 1
        $featureFlag1 = $workspace->getFeatureFlagOrNull(1);
        self::assertNotNull($featureFlag1);
        $this->identifier($featureFlag1, 4325, 1, ExperimentType::FEATURE_FLAG());
        $this->hasVariations(
            $featureFlag1,
            new Variation(13396, "A", false, null),
            new Variation(13397, "B", false, null)
        );
        $this->hasOverrides($featureFlag1, []);
        self::assertEquals(ExperimentStatus::PAUSED(), $featureFlag1->getStatus());


        //feature flag key = 2
        $featureFlag2 = $workspace->getFeatureFlagOrNull(2);
        self::assertNotNull($featureFlag2);
        $this->identifier($featureFlag2, 4326, 2, ExperimentType::FEATURE_FLAG());
        $this->hasVariations(
            $featureFlag2,
            new Variation(13398, "A", false, null),
            new Variation(13399, "B", false, null)
        );
        $this->hasOverrides($featureFlag2, []);
        self::assertEquals(ExperimentStatus::RUNNING(), $featureFlag2->getStatus());
        self::assertCount(0, $featureFlag2->getTargetAudiences());
        self::assertCount(0, $featureFlag2->getTargetRules());
        self::isEqualTargetAction(6118, $featureFlag2->getDefaultRule());

        //feature flag key = 3
        $featureFlag3 = $workspace->getFeatureFlagOrNull(3);
        self::assertNotNull($featureFlag3);
        $this->identifier($featureFlag3, 4327, 3, ExperimentType::FEATURE_FLAG());
        $this->hasVariations(
            $featureFlag3,
            new Variation(13400, "A", false, null),
            new Variation(13401, "B", false, null)
        );
        $this->hasOverrides($featureFlag3, []);
        self::assertEquals(ExperimentStatus::RUNNING(), $featureFlag3->getStatus());
        self::assertCount(0, $featureFlag3->getTargetAudiences());
        self::assertCount(0, $featureFlag3->getTargetRules());
        self::isEqualTargetAction(6121, $featureFlag3->getDefaultRule());

        //feature flag key = 4
        $featureFlag4 = $workspace->getFeatureFlagOrNull(4);
        self::assertNotNull($featureFlag4);
        $this->identifier($featureFlag4, 4328, 4, ExperimentType::FEATURE_FLAG());
        $this->hasVariations(
            $featureFlag4,
            new Variation(13402, "A", false, null),
            new Variation(13403, "B", false, null)
        );
        $this->hasOverrides(
            $featureFlag4,
            array("user1" => 13402, "user2" => 13403)
        );
        self::assertEquals(ExperimentStatus::RUNNING(), $featureFlag4->getStatus());
        self::assertCount(0, $featureFlag4->getTargetAudiences());
        self::assertCount(4, $featureFlag4->getTargetRules());
        self::assertEquals(
            new TargetRule(
                new Target(array(
                    new TargetCondition(
                        new TargetKey(TargetKeyType::FEATURE_FLAG(), "3"),
                        new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::BOOLEAN(), array(true))
                    )
                )),
                new TargetActionBucket(6125)
            ),
            $featureFlag4->getTargetRules()[0]
        );
        self::assertEquals(
            new TargetRule(
                new Target(array(
                    new TargetCondition(
                        new TargetKey(TargetKeyType::USER_PROPERTY(), "device"),
                        new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), array("ios"))
                    ),
                    new TargetCondition(
                        new TargetKey(TargetKeyType::USER_PROPERTY(), "version"),
                        new TargetMatch(
                            MatchType::MATCH(),
                            MatchOperator::IN(),
                            ValueType::STRING(),
                            array("2.0.0", "2.1.0")
                        )
                    )
                )),
                new TargetActionBucket(6126)
            ),
            $featureFlag4->getTargetRules()[1]
        );
        self::assertEquals(
            new TargetRule(
                new Target(array(
                    new TargetCondition(
                        new TargetKey(TargetKeyType::USER_PROPERTY(), "grade"),
                        new TargetMatch(
                            MatchType::MATCH(),
                            MatchOperator::IN(),
                            ValueType::STRING(),
                            array("GOLD", "SILVER")
                        )
                    )
                )),
                new TargetActionVariation(13403)
            ),
            $featureFlag4->getTargetRules()[2]
        );
        self::assertEquals(
            new TargetRule(
                new Target(array(
                    new TargetCondition(
                        new TargetKey(TargetKeyType::USER_PROPERTY(), "grade"),
                        new TargetMatch(MatchType::MATCH(), MatchOperator::IN(), ValueType::STRING(), array("BRONZE"))
                    )
                )),
                new TargetActionVariation(13402)
            ),
            $featureFlag4->getTargetRules()[3]
        );
        self::isEqualTargetAction(6124, $featureFlag4->getDefaultRule());


        //bucket id = 5823
        $bucket5823 = $workspace->getBucketOrNull(5823);
        self::assertNotNull($bucket5823);
        self::assertEquals(875758774, $bucket5823->getSeed());
        self::assertEquals(10000, $bucket5823->getSlotSize());
        for ($slotNumber = 0; $slotNumber <= 9999; $slotNumber++) {
            self::assertNull($bucket5823->getSlotOrNull($slotNumber));
        }

        //bucket id = 5823
        $bucket5829 = $workspace->getBucketOrNull(5829);
        self::assertNotNull($bucket5829);
        self::assertEquals(1634243589, $bucket5829->getSeed());
        self::assertEquals(10000, $bucket5829->getSlotSize());
        $this->slot($bucket5829, 0, 667, 12919);
        $this->slot($bucket5829, 667, 1333, 12920);
        $this->slot($bucket5829, 1333, 2000, 12921);

        //bucket id = 6106
        $bucket6106 = $workspace->getBucketOrNull(6106);
        self::assertNotNull($bucket6106);
        self::assertEquals(789801074, $bucket6106->getSeed());
        self::assertEquals(10000, $bucket6106->getSlotSize());
        $this->slot($bucket6106, 0, 3333, 13387);
        $this->slot($bucket6106, 3333, 6667, 13388);
        $this->slot($bucket6106, 6667, 10000, 13389);

        //bucket id = 6112
        $bucket6112 = $workspace->getBucketOrNull(6112);
        self::assertNotNull($bucket6112);
        self::assertEquals(2026965524, $bucket6112->getSeed());
        self::assertEquals(10000, $bucket6112->getSlotSize());
        $this->slot($bucket6112, 0, 250, 13392);
        $this->slot($bucket6112, 250, 500, 13393);
        $this->slot($bucket6112, 500, 750, 13394);
        $this->slot($bucket6112, 750, 1000, 13395);

        $this->slot($bucket6112, 1000, 2000, 13392);
        $this->slot($bucket6112, 2000, 3000, 13393);
        $this->slot($bucket6112, 3000, 4000, 13394);
        $this->slot($bucket6112, 4000, 5000, 13395);

        $this->slot($bucket6112, 5000, 6250, 13392);
        $this->slot($bucket6112, 6250, 7500, 13393);
        $this->slot($bucket6112, 7500, 8750, 13394);
        $this->slot($bucket6112, 8750, 10000, 13395);

        //bucket id = 6115
        $bucket6115 = $workspace->getBucketOrNull(6115);
        self::assertNotNull($bucket6115);
        self::assertEquals(228721685, $bucket6115->getSeed());
        self::assertEquals(10000, $bucket6115->getSlotSize());
        $this->slot($bucket6115, 0, 10000, 13396);

        // event types
        self::assertEquals(new EventType(3072, "a"), $workspace->getEventTypeOrNull("a"));
        self::assertEquals(new EventType(3073, "b"), $workspace->getEventTypeOrNull("b"));
        self::assertEquals(new EventType(3074, "c"), $workspace->getEventTypeOrNull("c"));
        self::assertEquals(new EventType(3075, "d"), $workspace->getEventTypeOrNull("d"));

        // parameter configuration
        self::assertNull($workspace->getParameterConfigurationOrNull(999));
        $parameterConfiguration = $workspace->getParameterConfigurationOrNull(1);
        self::assertNotNull($parameterConfiguration);
        self::assertEquals(1, $parameterConfiguration->getId());
        self::assertEquals("string_value_1", $parameterConfiguration->getString("string_key_1", "!!"));
        self::assertTrue($parameterConfiguration->getBool("boolean_key_1", false));
        self::assertEquals(2147483647, $parameterConfiguration->getInt("int_key_1", -1));
        self::assertEquals(42, $parameterConfiguration->getInt("int_key_2", -1));
        self::assertEquals(320, $parameterConfiguration->getInt("double_key_1", -1));
        self::assertEquals(92147483647, $parameterConfiguration->getInt("long_key_1", -1));
        self::assertEquals(320.1523, $parameterConfiguration->getFloat("double_key_1", -1.0));
        self::assertEquals(2147483647.0, $parameterConfiguration->getFloat("int_key_1", -1.0));
        self::assertEquals(42.0, $parameterConfiguration->getFloat("int_key_2", -1.0));
        self::assertEquals("{\"json_key\": \"json_value\"}", $parameterConfiguration->getString("json_key_1", "!!"));
    }

    public function testUnsupportedTypeTest()
    {
        $workspaceFetcher = new ResourcesWorkspaceFetcher(
            __DIR__ . "/../../Resources/unsupported_type_workspace_config.json"
        );

        $workspace = $workspaceFetcher->fetch();

        $experiment = $workspace->getExperimentOrNull(1);
        self::assertNotNull($experiment);
        self::assertEquals(ExperimentStatus::RUNNING(), $experiment->getStatus());
        self::assertCount(0, $experiment->getTargetAudiences());
        self::assertCount(0, $experiment->getTargetRules());

        self::assertNull($workspace->getExperimentOrNull(22));

        self::assertNull($workspace->getExperimentOrNull(23));

        $featureFlag = $workspace->getExperimentOrNull(1);
        self::assertNotNull($featureFlag);
        self::assertEquals(ExperimentStatus::RUNNING(), $featureFlag->getStatus());
        self::assertCount(0, $featureFlag->getTargetAudiences());
        self::assertCount(0, $featureFlag->getTargetRules());
    }

    private function isEqualTargetAction(int $expected, TargetAction $actual)
    {
        if ($actual instanceof TargetActionBucket) {
            self::assertEquals($expected, $actual->getBucketId());
        }
    }

    private function identifier(Experiment $actual, int $id, int $key, ExperimentType $type)
    {
        self::assertEquals($id, $actual->getId());
        self::assertEquals($key, $actual->getKey());
        self::assertEquals($type, $actual->getType());
    }

    private function hasVariations(Experiment $actual, Variation ...$variations)
    {
        self::assertEquals($variations, $actual->getVariations());
    }

    private function hasOverrides(Experiment $actual, array $overrides)
    {
        self::assertEquals($overrides, $actual->getUserOverrides());
    }

    private function slot(Bucket $actual, int $startInclusive, int $endExclusive, int $variationId)
    {
        for ($slotNumber = $startInclusive; $slotNumber < $endExclusive; $slotNumber++) {
            $slot = $actual->getSlotOrNull($slotNumber);
            self::assertNotNull($slot);
            self::assertEquals($variationId, $slot->getVariationId());
        }
    }
}
