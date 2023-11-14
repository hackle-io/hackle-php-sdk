<?php

namespace Hackle\Tests\Internal\Http;

use GuzzleHttp\Psr7\Response;
use Hackle\Internal\Http\Https;
use PHPUnit\Framework\TestCase;

class HttpsTest extends TestCase
{

    public function test__isSuccessful()
    {
        for ($i = 200; $i < 300; $i++) {
            $response = new Response($i);
            self::assertTrue(Https::isSuccessful($response));
        }
        self::assertFalse(Https::isSuccessful(new Response(199)));
        self::assertFalse(Https::isSuccessful(new Response(300)));
        self::assertFalse(Https::isSuccessful(new Response(500)));
        self::assertFalse(Https::isSuccessful(new Response(400)));
    }

    public function test__isNotModified()
    {
        self::assertTrue(Https::isNotModified(new Response(304)));
        self::assertFalse(Https::isNotModified(new Response(200)));
    }

    public function test__lastModified()
    {
        self::assertEquals("42", Https::lastModified(new Response(200, ["Last-Modified" => "42"])));
        self::assertNull(Https::lastModified(new Response(200)));
    }
}
