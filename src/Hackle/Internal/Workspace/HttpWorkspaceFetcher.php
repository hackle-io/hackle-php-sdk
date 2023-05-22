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
    private $_baseUri;

    /** @var Client */
    private $_client;

    /** @var LoggerInterface */
    private $_logger;

    /**
     * @param string $_baseUri
     * @param Client $_client
     * @param LoggerInterface $_logger
     */
    public function __construct(string $_baseUri, Client $_client, LoggerInterface $_logger)
    {
        $this->_baseUri = $_baseUri;
        $this->_client = $_client;
        $this->_logger = $_logger;
    }

    public function fetch(): ?Workspace
    {
        try {
            return $this->fetchInternal();
        } catch (Throwable $e) {
            $this->_logger->error("Failed fetch workspace : " . $e->getMessage());
            return null;
        }
    }

    /**
     * @throws Throwable
     */
    private function fetchInternal(): Workspace
    {
        $response = $this->_client->request("GET", $this->_baseUri . self::SDK_ENDPOINT_URI);
        if (!Https::isSuccessful($response)) {
            throw new RuntimeException("Http status code: " . $response->getStatusCode());
        }
        $body = $response->getBody();
        return DefaultWorkspace::from(json_decode($body->getContents(), true));
    }
}
