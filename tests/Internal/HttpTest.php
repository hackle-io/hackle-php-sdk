<?php

namespace Internal;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Hackle\HackleClients;
use Hackle\Internal\Http\SdkHeaderMiddleware;
use Hackle\Internal\Workspace\Sdk;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\FlysystemStorage;
use Kevinrob\GuzzleCache\Storage\Psr6CacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testHttp()
    {
        //$stack = HandlerStack::create();
        ////$stack->push(new CacheMiddleware(new PrivateCacheStrategy(new FlysystemStorage(new Local("/tmp/hackle/")))), 'cache');
        //
        //$fileSystemAdapter = new Local("/tmp/hackle/");
        //$fileSystem = new Filesystem($fileSystemAdapter);
        //$cacheStorage = new Psr6CacheStorage(new FilesystemCachePool($fileSystem));
        //
        //$stack->push(new CacheMiddleware(new GreedyCacheStrategy($cacheStorage, 10)), 'cache');
        //$client = new Client([// Base URI is used with relative requests
        //    // You can set any number of default request options.
        //    'timeout' => 10.0, 'handler' => $stack]);
        //$requestOptions = array();
        //$sdk = new Sdk('4Wiz7pve4rBEeQ7SHAF5nIAkLXHuiKz2');
        //$request = new Request('GET', 'https://sdk.hackle.io/api/v2/workspaces', [self::SDK_KEY_HEADER => $sdk->getKey(), self::SDK_NAME_HEADER => $sdk->getName(), self::SDK_VERSION_HEADER => $sdk->getVersion(), self::SDK_TIME_HEADER => 1684129063368]);
        //
        //print_r($request->getHeaders());
        //print($request->getBody());
        //$response = $client->send($request);
        //print($response->getBody());

        HackleClients::create('9jkNJPmsu7Z8b59OadBaY7thB0vadFyT', null);
    }

    private const SDK_KEY_HEADER = "X-HACKLE-SDK-KEY";
    private const SDK_NAME_HEADER = "X-HACKLE-SDK-NAME";
    private const SDK_VERSION_HEADER = "X-HACKLE-SDK-VERSION";
    private const SDK_TIME_HEADER = "X-HACKLE-SDK-TIME";
}
