<?php

namespace Hackle\Internal\Workspace;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use Hackle\Internal\User\Workspace\Workspace;
use Psr\Log\LoggerInterface;

class HttpWorkspaceFetcher
{
    private const SDK_ENDPOINT_URI = "/api/v2/workspaces";

    private $_baseUri;

    /** @var Client */
    private $_client;

    /** @var LoggerInterface */
    private $_logger;

    public function __construct($_baseUri, Client $_client, LoggerInterface $_logger)
    {
        $this->_baseUri = $_baseUri;
        $this->_client = $_client;
        $this->_logger = $_logger;
    }

    public function fetch(): void
    {
        $this->fetchInternal();
    }

    private function fetchInternal(): void
    {
        $response = $this->_client->get($this->_baseUri . self::SDK_ENDPOINT_URI);
        $body = $response->getBody();
        print($body);
    }
}
