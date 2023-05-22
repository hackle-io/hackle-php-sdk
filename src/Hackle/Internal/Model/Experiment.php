<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Lang\Pair;
use Hackle\Internal\Utils\Arrays;

class Experiment
{
    private $id;
    private $key;
    private $type;
    private $identifierType;
    private $status;
    private $version;
    private $variations;
    private $userOverrides;
    private $segmentOverrides;
    private $targetAudiences;
    private $targetRules;
    private $defaultRule;
    private $containerId;
    private $winnerVariationId;

    /**
     * @param int $id
     * @param int $key
     * @param ExperimentType $type
     * @param string $identifierType
     * @param ExperimentStatus $status
     * @param int $version
     * @param Variation[] $variations
     * @param array<string, int> $userOverrides
     * @param TargetRule[] $segmentOverrides
     * @param Target[] $targetAudiences
     * @param TargetRule[] $targetRules
     * @param TargetAction $defaultRule
     * @param int|null $containerId
     * @param int|null $winnerVariationId
     */
    public function __construct(
        int $id,
        int $key,
        ExperimentType $type,
        string $identifierType,
        ExperimentStatus $status,
        int $version,
        array $variations,
        array $userOverrides,
        array $segmentOverrides,
        array $targetAudiences,
        array $targetRules,
        TargetAction $defaultRule,
        ?int $containerId,
        ?int $winnerVariationId
    ) {
        $this->id = $id;
        $this->key = $key;
        $this->type = $type;
        $this->identifierType = $identifierType;
        $this->status = $status;
        $this->version = $version;
        $this->variations = $variations;
        $this->userOverrides = $userOverrides;
        $this->segmentOverrides = $segmentOverrides;
        $this->targetAudiences = $targetAudiences;
        $this->targetRules = $targetRules;
        $this->defaultRule = $defaultRule;
        $this->containerId = $containerId;
        $this->winnerVariationId = $winnerVariationId;
    }

    public function getVariationOrNullById(int $variationId): ?Variation
    {
        foreach ($this->variations as $variation) {
            if ($variation->getId() === $variationId) {
                return $variation;
            }
        }
        return null;
    }

    public function getVariationOrNullByKey(string $variationKey): ?Variation
    {
        foreach ($this->variations as $variation) {
            if ($variation->getKey() === $variationKey) {
                return $variation;
            }
        }
        return null;
    }

    public function getWinnerVariation(): ?Variation
    {
        if ($this->getWinnerVariationId() === null) {
            return null;
        }
        return $this->getVariationOrNullById($this->getWinnerVariationId());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getKey(): int
    {
        return $this->key;
    }

    /**
     * @return ExperimentType
     */
    public function getType(): ExperimentType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getIdentifierType(): string
    {
        return $this->identifierType;
    }

    /**
     * @return ExperimentStatus
     */
    public function getStatus(): ExperimentStatus
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return Variation[]
     */
    public function getVariations(): array
    {
        return $this->variations;
    }

    /**
     * @return int[]
     */
    public function getUserOverrides(): array
    {
        return $this->userOverrides;
    }

    /**
     * @return TargetRule[]
     */
    public function getSegmentOverrides(): array
    {
        return $this->segmentOverrides;
    }

    /**
     * @return Target[]
     */
    public function getTargetAudiences(): array
    {
        return $this->targetAudiences;
    }

    /**
     * @return TargetRule[]
     */
    public function getTargetRules(): array
    {
        return $this->targetRules;
    }

    /**
     * @return TargetAction
     */
    public function getDefaultRule(): TargetAction
    {
        return $this->defaultRule;
    }

    /**
     * @return int|null
     */
    public function getContainerId(): ?int
    {
        return $this->containerId;
    }

    /**
     * @return int|null
     */
    public function getWinnerVariationId(): ?int
    {
        return $this->winnerVariationId;
    }

    /**
     * @param array<string, mixed> $data
     * @return ?Experiment
     */
    public static function fromOrNull(array $data, ExperimentType $experimentType): ?Experiment
    {
        $executionData = $data["execution"];
        $experimentStatus = ExperimentStatus::fromOrNull($executionData["status"]);
        if ($experimentStatus === null) {
            return null;
        }

        $defaultRule = TargetAction::fromOrNull($executionData["defaultRule"]);
        if ($defaultRule === null) {
            return null;
        }

        return new Experiment(
            $data["id"],
            $data["key"],
            $experimentType,
            $data["identifierType"],
            $experimentStatus,
            $data["version"],
            array_map(function ($data) {
                return Variation::from($data);
            }, $data["variations"]),
            Arrays::associate($executionData["userOverrides"], function ($d) {
                return new Pair($d["userId"], $d["variationId"]);
            }),
            Arrays::mapNotNull($executionData["segmentOverrides"], function ($data) {
                return TargetRule::fromOrNull($data, TargetingType::IDENTIFIER());
            }),
            Arrays::mapNotNull($executionData["targetAudiences"], function ($data) {
                return Target::fromOrNull($data, TargetingType::PROPERTY());
            }),
            Arrays::mapNotNull($executionData["targetRules"], function ($data) {
                return TargetRule::fromOrNull($data, TargetingType::PROPERTY());
            }),
            $defaultRule,
            $data["containerId"],
            $data["winnerVariationId"]
        );
    }
}
