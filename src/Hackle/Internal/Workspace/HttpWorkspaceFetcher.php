<?php

namespace Hackle\Internal\Workspace;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Strategy\PublicCacheStrategy;
use Psr\Log\LoggerInterface;

class HttpWorkspaceFetcher
{
    private $_logger;
    private $_client;

    private $_endpoint;

    public function __construct(string $baseUri, string $sdkKey, array $options)
    {
        $this->_logger = $options['logger'];
        $stack = HandlerStack::create();
        if (class_exists('\Kevinrob\GuzzleCache\CacheMiddleware')) {
            $stack->push(
                new CacheMiddleware(
                    new PublicCacheStrategy($options['cache'] ?? null)
                ),
                'cache'
            );
        } else {
            $this->_logger->info("HttpWorkspaceFetcher is not using an HTTP cache because Kevinrob\GuzzleCache\CacheMiddleware was not installed");
        }

        $defaults = [
            'headers' => Util::defaultHeaders($sdkKey, $options['application_info'] ?? null),
            'timeout' => $options['timeout'],
            'connect_timeout' => $options['connect_timeout'],
            'handler' => $stack,
            'debug' => $options['debug'] ?? false,
            'base_uri' => $baseUri
        ];

        $this->_client = new Client($defaults);
    }

    public function getFeature(string $key): ?FeatureFlag
    {
        try {
            $response = $this->_client->get($this->_endpoint);
            $body = $response->getBody();
            return FeatureFlag::decode(json_decode($body->getContents(), true));
        } catch (BadResponseException $e) {
            /** @psalm-suppress PossiblyNullReference (resolved in guzzle 7) */
            $code = $e->getResponse()->getStatusCode();
            if ($code == 404) {
                $this->_logger->warning("GuzzleFeatureRequester::get returned 404. Feature flag does not exist for key: " . $key);
            } else {
                $this->handleUnexpectedStatus($code, "GuzzleFeatureRequester::get");
            }
            return null;
        }
    }
}
