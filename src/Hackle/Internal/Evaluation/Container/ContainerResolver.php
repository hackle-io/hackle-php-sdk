<?php

namespace Hackle\Internal\Evaluation\Container;

use Hackle\Internal\Evaluation\Bucket\Bucketer;
use Hackle\Internal\Evaluation\Evaluator\Experiment\ExperimentRequest;
use Hackle\Internal\Lang\Objects;
use Hackle\Internal\Model\Container;

final class ContainerResolver
{
    private $buckter;

    public function __construct(Bucketer $buckter)
    {
        $this->buckter = $buckter;
    }

    public function isUserInContainerGroup(ExperimentRequest $request, Container $container): bool
    {
        $experiment = $request->getExperiment();
        $identifier = $request->getUser()->getIdentifiers()[$experiment->getIdentifierType()] ?? null;
        if ($identifier === null) {
            return false;
        }
        $bucket = Objects::requireNotNull(
            $request->getWorkspace()->getBucketOrNull($container->getBucketId()),
            "Bucket[{$container->getBucketId()}]"
        );
        $slot = $this->buckter->bucketing($bucket, $identifier);
        if ($slot === null) {
            return false;
        }
        $containerGroup = Objects::requireNotNull(
            $container->getGroupOrNull($slot->getVariationId()),
            "ContainerGroup[{$slot->getVariationId()}]"
        );
        return in_array($experiment->getId(), $containerGroup->getExperiments());
    }
}
