<?php

namespace Hackle\Internal\Http;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\Psr6CacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Psr\Log\LoggerInterface;

class SdkCacheMiddleware implements HackleMiddleware
{
    /** @var string */
    private $path;

    /** @var int */
    private $ttl;

    /**@var LoggerInterface */
    private $logger;

    /**
     * @param string $path
     * @param int $ttl
     * @param LoggerInterface $logger
     */
    public function __construct(string $path, int $ttl, LoggerInterface $logger)
    {
        $this->path = $path;
        $this->ttl = $ttl;
        $this->logger = $logger;
    }

    public function process(HandlerStack $stack)
    {
        if (class_exists('\Kevinrob\GuzzleCache\CacheMiddleware')) {
            $fileSystemAdapter = new Local($this->path);
            $fileSystem = new Filesystem($fileSystemAdapter);
            $cacheStorage = new Psr6CacheStorage(new FilesystemCachePool($fileSystem));
            $stack->push(new CacheMiddleware(new GreedyCacheStrategy($cacheStorage, $this->ttl)), 'cache');
        } else {
            $this->logger->error("Hackle - SdkCacheMiddleware is not using an HTTP cache because Kevinrob\GuzzleCache\CacheMiddleware was not installed");
        }
    }
}
