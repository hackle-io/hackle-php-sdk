<?php

namespace Hackle\Tests\Internal\Workspace;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Hackle\Internal\Workspace\HttpWorkspaceFetcher;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class HttpWorkspaceFetcherTest extends TestCase
{
    public function testGetFail()
    {
        $httpClient = $this->createMock(Client::class);
        $httpClient->expects($this->once())->method("request")->willReturn(new Response(500));
        $sut = new HttpWorkspaceFetcher("localhost", $httpClient, new Logger("Hackle"));
        $workspace = $sut->fetch();
        $this->assertNull($workspace);
    }

    public function testGetWorkspace()
    {
        $responseBody = file_get_contents(__DIR__ . "/../../Resources/workspace_config.json");
        $httpClient = $this->createMock(Client::class);
        $httpClient->expects($this->once())->method("request")->willReturn(new Response(200, [], $responseBody));
        $sut = new HttpWorkspaceFetcher("localhost", $httpClient, new Logger("Hackle"));
        $workspace = $sut->fetch();
        $this->assertNotNull($workspace);
    }
}
