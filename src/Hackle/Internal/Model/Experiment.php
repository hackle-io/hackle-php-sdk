<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Model\Enums\ExperimentStatus;
use Hackle\Internal\Model\Enums\ExperimentType;

class Experiment
{
    /**@var int */
    private $_id;

    /**@var int */
    private $_key;

    /** @var string */
    private $_type;

    /**@var string */
    private $_identifierType;

    /** @var ExperimentStatus */
    private $_status;

    /**@var int */
    private $_version;

    /** @var Variation[] */
    private $_variations;

    /** @var int[] */
    private $_userOverrides;

    /** @var TargetRule[] */
    private $_segmentOverrides;

    /** @var Target[] */
    private $_targetAudiences;

    /** @var TargetRule[] */
    private $_targetRules;

    /** @var Action */
    private $_defaultRule;

    /** @var int|null */
    private $_containerId;

    /** @var int|null */
    private $_winnerVariationId;

    /**
     * @param int $_id
     * @param int $_key
     * @param ExperimentType $_type
     * @param string $_identifierType
     * @param ExperimentStatus $_status
     * @param int $_version
     * @param Variation[] $_variations
     * @param array<string, int> $_userOverrides
     * @param TargetRule[] $_segmentOverrides
     * @param Target[] $_targetAudiences
     * @param TargetRule[] $_targetRules
     * @param Action $_defaultRule
     * @param int|null $_containerId
     * @param int|null $_winnerVariationId
     */
    public function __construct(
        int $_id,
        int $_key,
        string $_type,
        string $_identifierType,
        ExperimentStatus $_status,
        int $_version,
        array $_variations,
        array $_userOverrides,
        array $_segmentOverrides,
        array $_targetAudiences,
        array $_targetRules,
        Action $_defaultRule,
        ?int $_containerId,
        ?int $_winnerVariationId
    ) {
        $this->_id = $_id;
        $this->_key = $_key;
        $this->_type = $_type;
        $this->_identifierType = $_identifierType;
        $this->_status = $_status;
        $this->_version = $_version;
        $this->_variations = $_variations;
        $this->_userOverrides = $_userOverrides;
        $this->_segmentOverrides = $_segmentOverrides;
        $this->_targetAudiences = $_targetAudiences;
        $this->_targetRules = $_targetRules;
        $this->_defaultRule = $_defaultRule;
        $this->_containerId = $_containerId;
        $this->_winnerVariationId = $_winnerVariationId;
    }


    public function getVariationOrNullById(int $variationId): ?Variation
    {
        $variations = array_filter($this->_variations, function (Variation $variation) use ($variationId) {
            return $variation->getId() == $variationId;
        });
        if (empty($variations)) {
            return null;
        }
        return array_values($variations)[0];
    }

    public function getVariationOrNullByKey(string $variationKey): ?Variation
    {
        $variations = array_filter($this->_variations, function (Variation $variation) use ($variationKey) {
            return $variation->getKey() == $variationKey;
        });
        if (empty($variations)) {
            return null;
        }
        return array_values($variations)[0];
    }

    public function getWinnerVariation(): ?Variation
    {
        if (!empty($this->_winnerVariationId)) {
            return $this->getVariationOrNullById($this->_winnerVariationId);
        }
        return null;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * @return int
     */
    public function getKey(): int
    {
        return $this->_key;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * @return string
     */
    public function getIdentifierType(): string
    {
        return $this->_identifierType;
    }

    /**
     * @return ExperimentStatus
     */
    public function getStatus(): ExperimentStatus
    {
        return $this->_status;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->_version;
    }

    /**
     * @return Variation[]
     */
    public function getVariations(): array
    {
        return $this->_variations;
    }

    /**
     * @return array<string, int>
     */
    public function getUserOverrides(): array
    {
        return $this->_userOverrides;
    }

    /**
     * @return TargetRule[]
     */
    public function getSegmentOverrides(): array
    {
        return $this->_segmentOverrides;
    }

    /**
     * @return Target[]
     */
    public function getTargetAudiences(): array
    {
        return $this->_targetAudiences;
    }

    /**
     * @return TargetRule[]
     */
    public function getTargetRules(): array
    {
        return $this->_targetRules;
    }

    /**
     * @return Action
     */
    public function getDefaultRule(): Action
    {
        return $this->_defaultRule;
    }

    /**
     * @return int|null
     */
    public function getContainerId(): ?int
    {
        return $this->_containerId;
    }

    /**
     * @return int|null
     */
    public function getWinnerVariationId(): ?int
    {
        return $this->_winnerVariationId;
    }
}
