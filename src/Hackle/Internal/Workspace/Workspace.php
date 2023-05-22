<?php

namespace Hackle\Internal\Workspace;

use Hackle\Internal\Model\Bucket;
use Hackle\Internal\Model\Container;
use Hackle\Internal\Model\EventType;
use Hackle\Internal\Model\Experiment;
use Hackle\Internal\Model\ParameterConfiguration;
use Hackle\Internal\Model\RemoteConfigParameter;
use Hackle\Internal\Model\Segment;

interface Workspace
{
    public function getExperimentOrNull(int $experimentKey): ?Experiment;

    public function getFeatureFlagOrNull(int $featureKey): ?Experiment;

    public function getEventTypeOrNull(string $eventTypeKey): ?EventType;

    public function getBucketOrNull(int $bucketId): ?Bucket;

    public function getSegmentOrNull(string $segmentKey): ?Segment;

    public function getContainerOrNull(int $containerId): ?Container;

    public function getParameterConfigurationOrNull(int $parameterConfigurationId): ?ParameterConfiguration;

    public function getRemoteConfigParameterOrNull(string $parameterKey): ?RemoteConfigParameter;
}