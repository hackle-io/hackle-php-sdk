<?php

namespace Hackle\Tests\Internal\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Hackle\Internal\Http\SdkHeaderMiddleware;
use Hackle\Internal\Model\Sdk;
use Hackle\Tests\Internal\Time\FixedClock;
use PHPUnit\Framework\TestCase;

class SdkHeaderMiddlewareTest extends TestCase
{

    public function test__mapRequest__inject_sdk_header_into_request()
    {
        // given
        $mock = new MockHandler([new Response(200)]);
        $stack = HandlerStack::create($mock);

        $sdk = new Sdk("SDK_KEY");
        $clock = new FixedClock(42, 42);
        $stack->push(new SdkHeaderMiddleware($sdk, $clock));

        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);

        $client = new Client(["handler" => $stack]);

        // when
        $client->request("GET", "/");

        // then
        self::assertCount(1, $container);
        $request = $container[0]["request"];
        self::assertEquals("SDK_KEY", $request->getHeaderLine("X-HACKLE-SDK-KEY"));
        self::assertEquals("php-sdk", $request->getHeaderLine("X-HACKLE-SDK-NAME"));
        self::assertNotNull($request->getHeaderLine("X-HACKLE-SDK-VERSION"));
        self::assertEquals("42", $request->getHeaderLine("X-HACKLE-SDK-TIME"));
    }
}
