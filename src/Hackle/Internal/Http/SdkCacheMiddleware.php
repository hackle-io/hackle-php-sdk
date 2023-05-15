<?php

namespace Hackle\Internal\Http;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\CacheStorageInterface;
use Kevinrob\GuzzleCache\Storage\DoctrineCacheStorage;
use Kevinrob\GuzzleCache\Storage\Psr6CacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Psr\Log\LoggerInterface;

class SdkCacheMiddleware implements HackleMiddleware
{
    private $_path;

    private $_ttl;

    /**@var LoggerInterface */
    private $_logger;

    public function __construct(string $_path, int $_ttl, LoggerInterface $_logger)
    {
        $this->_path = $_path;
        $this->_ttl = $_ttl;
        $this->_logger = $_logger;
    }

    public function process(HandlerStack $stack)
    {
        $stack = HandlerStack::create();
        if (class_exists('\Kevinrob\GuzzleCache\CacheMiddleware')) {
            $stack->push(new CacheMiddleware(new GreedyCacheStrategy($this->getCacheStorage(), $this->_ttl)), 'cache');
        } else {
            $this->_logger->info("Hackle - SdkCacheMiddleware is not using an HTTP cache because Kevinrob\GuzzleCache\CacheMiddleware was not installed");
        }
    }

    private function getCacheStorage(): CacheStorageInterface
    {
        $fileSystemAdapter = new Local($this->_path);
        $fileSystem = new Filesystem($fileSystemAdapter);
        return new Psr6CacheStorage(new FilesystemCachePool($fileSystem));
    }
}
