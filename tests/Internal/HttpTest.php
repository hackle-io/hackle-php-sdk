<?php

namespace Internal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Hackle\Internal\Http\SdkHeaderMiddleware;
use Hackle\Internal\Workspace\Sdk;
use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testHttp()
    {
        $client = new Client([// Base URI is used with relative requests
            // You can set any number of default request options.
            'timeout' => 10.0,]);
        $requestOptions = array();
        $sdk = new Sdk('4Wiz7pve4rBEeQ7SHAF5nIAkLXHuiKz2');
        $request = new Request('GET', 'https://sdk.hackle.io/api/v2/workspaces', [self::SDK_KEY_HEADER => $sdk->getKey(), self::SDK_NAME_HEADER => $sdk->getName(), self::SDK_VERSION_HEADER => $sdk->getVersion(), self::SDK_TIME_HEADER => 1684129063368]);
        print_r($request->getHeaders());
        print($request->getBody());
        $response = $client->send($request);
        print($response->getBody());
    }

    private const SDK_KEY_HEADER = "X-HACKLE-SDK-KEY";
    private const SDK_NAME_HEADER = "X-HACKLE-SDK-NAME";
    private const SDK_VERSION_HEADER = "X-HACKLE-SDK-VERSION";
    private const SDK_TIME_HEADER = "X-HACKLE-SDK-TIME";
}
