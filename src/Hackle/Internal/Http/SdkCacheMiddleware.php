<?php

namespace Hackle\Internal\Http;

use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Psr\Log\LoggerInterface;

class SdkCacheMiddleware implements HackleMiddleware
{
    /**@var CacheMiddleware */
    private $cache;

    /**@var LoggerInterface */
    private $_logger;

    public function __construct(CacheMiddleware $cache, LoggerInterface $_logger)
    {
        $this->cache = $cache;
        $this->_logger = $_logger;
    }

    public function process(HandlerStack $stack)
    {
        if (class_exists('\Kevinrob\GuzzleCache\CacheMiddleware')) {
            $stack->push($this->cache, 'cache');
        } else {
            $this->_logger->error("Hackle - SdkCacheMiddleware is not using an HTTP cache because Kevinrob\GuzzleCache\CacheMiddleware was not installed");
        }
    }
}
