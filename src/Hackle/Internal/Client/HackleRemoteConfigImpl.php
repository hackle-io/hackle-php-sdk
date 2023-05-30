<?php

namespace Hackle\Internal\Client;

use Hackle\Common\DecisionReason;
use Hackle\Common\RemoteConfig;
use Hackle\Common\RemoteConfigDecision;
use Hackle\Common\User;
use Hackle\Internal\Core\HackleCore;
use Hackle\Internal\Model\ValueType;
use Hackle\Internal\User\HackleUserResolver;
use Psr\Log\LoggerInterface;

class HackleRemoteConfigImpl implements RemoteConfig
{
    /**@var User */
    private $user;

    /**@var HackleCore */
    private $core;

    private $logger;

    /**
     * @param User $user
     * @param HackleCore $core
     * @param LoggerInterface $logger
     */
    public function __construct(User $user, HackleCore $core, LoggerInterface $logger)
    {
        $this->user = $user;
        $this->core = $core;
        $this->logger = $logger;
    }

    public function getString(string $key, $defaultValue)
    {
        return $this->get($this->user, $key, ValueType::STRING(), $defaultValue)->getValue();
    }

    public function getInt(string $key, $defaultValue)
    {
        return intval($this->get($this->user, $key, ValueType::NUMBER(), $defaultValue)->getValue());
    }

    public function getFloat(string $key, $defaultValue)
    {
        return floatval($this->get($this->user, $key, ValueType::NUMBER(), $defaultValue)->getValue());
    }

    public function getBool(string $key, $defaultValue)
    {
        return $this->get($this->user, $key, ValueType::BOOLEAN(), $defaultValue)->getValue();
    }

    private function get(User $user, string $key, ValueType $requiredType, $defaultValue): RemoteConfigDecision
    {
        try {
            $hackleUser = HackleUserResolver::resolveOrNull($user);
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
