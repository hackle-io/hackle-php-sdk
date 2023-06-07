<?php

namespace Hackle\Internal\Client;

use Hackle\Common\DecisionReason;
use Hackle\Common\RemoteConfig;
use Hackle\Common\RemoteConfigDecision;
use Hackle\Common\HackleUser;
use Hackle\Internal\Core\HackleCore;
use Hackle\Internal\Model\ValueType;
use Hackle\Internal\User\HackleUserResolver;
use Psr\Log\LoggerInterface;

class HackleRemoteConfigImpl implements RemoteConfig
{
    /**@var HackleUser */
    private $user;

    /**@var HackleCore */
    private $core;

    /** @var HackleUserResolver */
    private $userResolver;

    /**@var LoggerInterface */
    private $logger;

    /**
     * @param HackleUser $user
     * @param HackleCore $core
     * @param HackleUserResolver $userResolver
     * @param LoggerInterface $logger
     */
    public function __construct(HackleUser $user, HackleCore $core, HackleUserResolver $userResolver, LoggerInterface $logger)
    {
        $this->user = $user;
        $this->core = $core;
        $this->userResolver = $userResolver;
        $this->logger = $logger;
    }

    public function getString(string $key, $defaultValue)
    {
        return $this->get($this->user, $key, ValueType::STRING(), $defaultValue)->getValue();
    }

    public function getInt(string $key, $defaultValue)
    {
        $value = $this->get($this->user, $key, ValueType::NUMBER(), $defaultValue)->getValue();
        return is_numeric($value) ? intval($value) : $defaultValue;
    }

    public function getFloat(string $key, $defaultValue)
    {
        $value = $this->get($this->user, $key, ValueType::NUMBER(), $defaultValue)->getValue();
        return is_numeric($value) ? floatval($value) : $defaultValue;
    }

    public function getBool(string $key, $defaultValue)
    {
        return $this->get($this->user, $key, ValueType::BOOLEAN(), $defaultValue)->getValue();
    }

    private function get(HackleUser $user, string $key, ValueType $requiredType, $defaultValue): RemoteConfigDecision
    {
        try {
            $hackleUser = $this->userResolver->resolveOrNull($user);
            if ($hackleUser == null) {
                return RemoteConfigDecision::of($defaultValue, DecisionReason::INVALID_INPUT());
            }
            return $this->core->remoteConfig($key, $hackleUser, $requiredType, $defaultValue);
        } catch (\Exception $e) {
            $this->logger->error("Unexpected exception while deciding remote config parameter[" . $key . "]. Returning default value.");
            return RemoteConfigDecision::of($defaultValue, DecisionReason::EXCEPTION());
        }
    }
}
