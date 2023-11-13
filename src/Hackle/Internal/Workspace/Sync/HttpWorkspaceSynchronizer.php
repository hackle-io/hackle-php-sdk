<?php

namespace Hackle\Internal\Workspace\Sync;

use GuzzleHttp\Client;
use Hackle\Internal\Http\Https;
use Hackle\Internal\Model\Sdk;
use Hackle\Internal\Workspace\DefaultWorkspace;
use Hackle\Internal\Workspace\Workspace;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class HttpWorkspaceSynchronizer
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var WorkspaceRepository
     */
    private $repository;

    /**
     * @param string $sdkUrl
     * @param Sdk $sdk
     * @param Client $httpClient
     * @param WorkspaceRepository $repository
     */
    public function __construct(string $sdkUrl, Sdk $sdk, Client $httpClient, WorkspaceRepository $repository)
    {
        $this->url = $sdkUrl . "/api/v2/workspaces/" . $sdk->getKey() . "/config";
        $this->httpClient = $httpClient;
        $this->repository = $repository;
    }

    /**
     * @throws Throwable
     */
    public function sync(): Workspace
    {
        $headers = array();
        $lastModified = $this->repository->getLastModified();
        if ($lastModified !== null) {
            $headers[Https::IF_MODIFIED_SINCE] = $lastModified;
        }
        $response = $this->httpClient->request("GET", $this->url, ["headers" => $headers]);
        return $this->handleResponse($response);
    }

    private function handleResponse(ResponseInterface $response): Workspace
    {
        if (Https::isNotModified($response)) {
            return $this->resolveNotModified();
        }
        if (!Https::isSuccessful($response)) {
            throw new \RuntimeException("Http status code: {$response->getStatusCode()}");
        }
        return $this->resolveSuccessful($response);
    }

    private function resolveNotModified(): Workspace
    {
        $workspace = $this->repository->getWorkspace();
        if ($workspace === null) {
            throw new \RuntimeException("Workspace is null.");
        }
        return DefaultWorkspace::from(json_decode($workspace, true));
    }

    private function resolveSuccessful(ResponseInterface $response): Workspace
    {
        $responseBody = $response->getBody()->getContents();

        $this->repository->setLastModified(Https::lastModified($response));
        $this->repository->setWorkspace($responseBody);

        return DefaultWorkspace::from(json_decode($responseBody, true));
    }
}
