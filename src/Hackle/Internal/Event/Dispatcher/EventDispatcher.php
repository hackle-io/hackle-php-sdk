<?php

namespace Hackle\Internal\Event\Dispatcher;

use Exception;
use GuzzleHttp\Client;
use Hackle\Internal\Utils\Https;
use Psr\Log\LoggerInterface;
use RuntimeException;

class EventDispatcher
{
    private const SDK_ENDPOINT_URI = "/api/v2/events";

    /**@var string */
    private $baseUri;

    /**@var Client */
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


    public function dispatch(array $userEvents): void
    {
        try {
            $this->submit(json_encode(EventPayloadDto::toPayload($userEvents)));
        } catch (Exception $e) {
            $this->logger->error("Unexpected exception while submitting events for dispatch.");
        }
    }

    private function submit(string $payload): void
    {
        $options['body'] = $payload;
        $response = $this->client->request("POST", $this->baseUri . self::SDK_ENDPOINT_URI, $options);
        if (!Https::isSuccessful($response)) {
            throw new RuntimeException("Http status code: " . $response->getStatusCode());
        }
    }
}
