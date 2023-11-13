<?php

namespace Hackle\Internal\Logger;

use Psr\Log\LoggerInterface;

class Log
{
    /**
     * @var LoggerInterface
     */
    private static $instance;

    public static function initialize(LoggerInterface $logger)
    {
        self::$instance = $logger;
    }

    public static function error($message, array $context = array())
    {
        if (self::$instance === null) {
            return;
        }
        self::$instance->error($message, $context);
    }

    public static function warning($message, array $context = array())
    {
        if (self::$instance === null) {
            return;
        }
        self::$instance->warning($message, $context);
    }

    public static function info($message, array $context = array())
    {
        if (self::$instance === null) {
            return;
        }
        self::$instance->info($message, $context);
    }

    public static function debug($message, array $context = array())
    {
        if (self::$instance === null) {
            return;
        }
        self::$instance->debug($message, $context);
    }
}
