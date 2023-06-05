<?php

namespace Hackle\Internal\Workspace;

use Exception;
use GuzzleHttp\Client;
use Hackle\Internal\Utils\Https;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

class HttpWorkspaceFetcher implements WorkspaceFetcher
{
    private const SDK_ENDPOINT_URI = "/api/v2/workspaces";

    /** @var string */
    private $baseUri;

    /** @var Client */
    private $client;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param string $baseUri
     * @param Client $client
     * @param LoggerInterface $logger
     */
    public function __construct(string $baseUri, Client $client, LoggerInterface $logger)
    {
        $this->baseUri = $baseUri;
        $this->client = $client;
        $this->logger = $logger;
    }

    public function fetch(): ?Workspace
    {
        try {
            return $this->fetchInternal();
        } catch (Throwable $e) {
            $this->logger->error("Failed fetch workspace : " . $e->getMessage());
            return null;
        }
    }

    /**
     * @throws Throwable
     */
    private function fetchInternal(): Workspace
    {
        $response = $this->client->request("GET", $this->baseUri . self::SDK_ENDPOINT_URI);
        if (!Https::isSuccessful($response)) {
            throw new RuntimeException("Http status code: " . $response->getStatusCode());
        }
        $body = $response->getBody();
        return DefaultWorkspace::from(json_decode($body->getContents(), true));
    }
}
