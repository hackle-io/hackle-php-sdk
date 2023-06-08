<?php

namespace Hackle\Internal\Http;

use GuzzleHttp\HandlerStack;
use Hackle\Internal\Time\Clock;
use Hackle\Internal\Workspace\Sdk;
use Psr\Http\Message\RequestInterface;

class SdkHeaderMiddleware implements HackleMiddleware
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


    public function process(HandlerStack $stack)
    {
        $stack->push($this->addHeader(self::SDK_KEY_HEADER, $this->sdk->getKey()));
        $stack->push($this->addHeader(self::SDK_NAME_HEADER, $this->sdk->getName()));
        $stack->push($this->addHeader(self::SDK_VERSION_HEADER, $this->sdk->getVersion()));
        $stack->push($this->addHeader(self::SDK_TIME_HEADER, strval($this->clock->currentMillis())));
    }

    private function addHeader(string $header, $value): \Closure
    {
        return function (callable $handler) use ($header, $value) {
            return function (RequestInterface $request, array $options) use ($handler, $header, $value) {
                $request = $request->withHeader($header, $value);
                return $handler($request, $options);
            };
        };
    }
}
