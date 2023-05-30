<?php

namespace Hackle\Common;

interface Config
{
    public function getString(string $key, $defaultValue);

    public function getInt(string $key, $defaultValue);

    public function getFloat(string $key, $defaultValue);

    public function getBool(string $key, $defaultValue);
}
