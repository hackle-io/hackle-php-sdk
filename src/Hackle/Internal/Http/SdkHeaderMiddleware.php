<?php

namespace Hackle\Internal\Http;

use Hackle\Internal\Model\Sdk;
use Hackle\Internal\Time\Clock;
use Psr\Http\Message\RequestInterface;

class SdkHeaderMiddleware
{
    private const SDK_KEY_HEADER = "X-HACKLE-SDK-KEY";
    private const SDK_NAME_HEADER = "X-HACKLE-SDK-NAME";
    private const SDK_VERSION_HEADER = "X-HACKLE-SDK-VERSION";
    private const SDK_TIME_HEADER = "X-HACKLE-SDK-TIME";

    /**@var Sdk */
    private $sdk;

    /**@var Clock */
    private $clock;

    /**
     * @param Sdk $sdk
     * @param Clock $clock
     */
    public function __construct(Sdk $sdk, Clock $clock)
    {
        $this->sdk = $sdk;
        $this->clock = $clock;
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options = []) use ($handler) {
            $newRequest = $request
                ->withHeader(self::SDK_KEY_HEADER, $this->sdk->getKey())
                ->withHeader(self::SDK_NAME_HEADER, $this->sdk->getName())
                ->withHeader(self::SDK_VERSION_HEADER, $this->sdk->getVersion())
                ->withHeader(self::SDK_TIME_HEADER, strval($this->clock->currentMillis()));
            return $handler($newRequest, $options);
        };
    }
}
