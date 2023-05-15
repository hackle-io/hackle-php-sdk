<?php

namespace Hackle\Internal\Http;
use GuzzleHttp\HandlerStack;

interface HackleMiddleware
{
    public function process(HandlerStack $stack);
}