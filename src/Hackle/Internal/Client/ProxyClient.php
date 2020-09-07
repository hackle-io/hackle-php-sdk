<?php


namespace Hackle\Internal\Client;


use Exception;
use GuzzleHttp\Client;
use Hackle\ClientInterface;
use Hackle\Internal\Http\Guzzle;
use Psr\Log\LoggerInterface;

final class ProxyClient implements ClientInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * ProxyClient constructor.
     * @param LoggerInterface $logger
     * @param Client $httpClient
     */
    public function __construct(LoggerInterface $logger, Client $httpClient)
    {
        $this->logger = $logger;
        $this->httpClient = $httpClient;
    }

    public function variation($experimentKey, $userId, $defaultVariation = 'A')
    {
        if (is_null($experimentKey)) {
            return $defaultVariation;
        }

        if (is_null($userId) || empty($userId)) {
            return $defaultVariation;
        }

        $requestBody = [
            'experimentKey' => $experimentKey,
            'userId' => $userId,
            'defaultVariation' => $defaultVariation
        ];

        $requestOptions = [
            'body' => json_encode($requestBody),
            'headers' => ['Content-Type' => 'application/json']
        ];

        try {
            $response = $this->httpClient->post('/api/v1/variation', $requestOptions);
        } catch (Exception $e) {
            $this->logger->warning("Failed to decide variation. Replace with default variation ($defaultVariation): {$e->getMessage()}");
            return $defaultVariation;
        }

        $statusCode = $response->getStatusCode();
        if (!Guzzle::isSuccessful($statusCode)) {
            $this->logger->warning("Failed to decide variation. Http status code ($statusCode). Replace with default variation ($defaultVariation)");
            return $defaultVariation;
        }

        $responseBody = json_decode($response->getBody(), true);
        return $responseBody['variation'];
    }

    public function track($eventKey, $userId, $value = null)
    {
        if (is_null($eventKey) || empty($eventKey)) {
            return;
        }

        if (is_null($userId) || empty($userId)) {
            return;
        }

        $event = [
            'userId' => $userId,
            'eventKey' => $eventKey,
            'value' => $value
        ];

        $requestOptions = [
            'body' => json_encode($event),
            'headers' => ['Content-Type' => 'application/json']
        ];

        try {
            $this->httpClient->post('/api/v1/track', $requestOptions);
        } catch (Exception $e) {
            $this->logger->warning("Failed to dispatch track events: {$e->getMessage()}");
        }
    }
}