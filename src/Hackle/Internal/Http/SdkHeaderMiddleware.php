<?php

namespace Hackle\Internal\Http;

use GuzzleHttp\HandlerStack;
use Hackle\Internal\Workspace\Sdk;
use Psr\Http\Message\RequestInterface;

class SdkHeaderMiddleware implements HackleMiddleware
{
    private const SDK_KEY_HEADER = "X-HACKLE-SDK-KEY";
    private const SDK_NAME_HEADER = "X-HACKLE-SDK-NAME";
    private const SDK_VERSION_HEADER = "X-HACKLE-SDK-VERSION";
    private const SDK_TIME_HEADER = "X-HACKLE-SDK-TIME";

    /**@var Sdk */
    private $_sdk;

    public function __construct($_sdk)
    {
        $this->_sdk = $_sdk;
    }

    public function process(HandlerStack $stack)
    {
        $stack->push($this->addHeader(self::SDK_KEY_HEADER, $this->_sdk->getKey()));
        $stack->push($this->addHeader(self::SDK_NAME_HEADER, $this->_sdk->getName()));
        $stack->push($this->addHeader(self::SDK_VERSION_HEADER, $this->_sdk->getVersion()));
        $stack->push($this->addHeader(self::SDK_TIME_HEADER, 1684129063368));
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
