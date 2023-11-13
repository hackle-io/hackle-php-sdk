<?php

namespace Hackle\Tests\Internal\Workspace\Sync;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Hackle\Internal\Model\Sdk;
use Hackle\Internal\Workspace\Sync\HttpWorkspaceSynchronizer;
use Hackle\Internal\Workspace\Sync\WorkspaceRepository;
use Hackle\Tests\Internal\Repository\MemoryRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class HttpWorkspaceSynchronizerTest extends TestCase
{
    private $httpClient;
    private $repository;
    private $sut;

    public function setUp()
    {
        $sdk = new Sdk("SDK_KEY");
        $this->httpClient = Mockery::mock(Client::class);
        $this->repository = new WorkspaceRepository(new MemoryRepository(), $sdk);
        $this->sut = new HttpWorkspaceSynchronizer(
            "http://localhost",
            $sdk,
            $this->httpClient,
            $this->repository
        );
    }

    public function test__when_exception_on_http_call_then_throw_that_exception()
    {
        // given
        $this->httpClient->allows("request")->andThrow(new \Exception("fail"));

        // when
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("fail");
        $this->sut->sync();

        // then
        self::assertNull($this->repository->getWorkspace());
    }

    public function test__when_workspace_is_not_modified_but_not_cached_then_throw_exception()
    {
        // given
        $this->httpClient->allows("request")->andReturn(new Response(304));

        // when
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Workspace is null.");
        $this->sut->sync();
    }

    public function test__when_workspace_is_not_modified_then_return_caced_workspace()
    {
        // given
        $this->httpClient->allows("request")->andReturn(new Response(304));
        $this->repository->setWorkspace(file_get_contents(__DIR__ . "/../../../Resources/workspace_config.json"));

        // when
        $actual = $this->sut->sync();

        // then
        self::assertNotNull($actual->getExperimentOrNull(5));
    }

    public function test__when_http_call_is_not_successful_then_throw_exception()
    {
        $this->httpClient->allows("request")->andReturn(new Response(500));
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Http status code: 500");
        $this->sut->sync();
    }

    public function test__when_successful_to_fetch_workspace_then_cache_and_return_workspace()
    {
        // given
        $json = file_get_contents(__DIR__ . "/../../../Resources/workspace_config.json");
        $this->httpClient->allows("request")->andReturn(new Response(200, [], $json));

        // when
        $actual = $this->sut->sync();

        // then
        self::assertNotNull($actual->getExperimentOrNull(5));
        self::assertEquals($json, $this->repository->getWorkspace());
    }

    public function test__last_modified()
    {
        $call = [];
        $json = file_get_contents(__DIR__ . "/../../../Resources/workspace_config.json");
        $stack = HandlerStack::create(
            new MockHandler([
                new Response(200, ["Last-Modified" => "LAST_MODIFIED_HEADER_VALUE"], $json),
                new Response(304)
            ])
        );
        $stack->push(Middleware::history($call));

        $httpClient = new Client(["handler" => $stack]);

        $sut = new HttpWorkspaceSynchronizer(
            "http://localhost",
            new Sdk("SDK_KEY"),
            $httpClient,
            $this->repository
        );

        self::assertNull($this->repository->getLastModified());

        $sut->sync();
        self::assertEmpty($call[0]["request"]->getHeaderLine("If-Modified-Since"));
        self::assertEquals("LAST_MODIFIED_HEADER_VALUE", $this->repository->getLastModified());

        $sut->sync();
        self::assertEquals("LAST_MODIFIED_HEADER_VALUE", $call[1]["request"]->getHeaderLine("If-Modified-Since"));
    }
}
