<?php

namespace Hackle\Common;

interface Config
{
    /**
     * @param string $key
     * @param string|mixed $defaultValue
     * @return mixed
     */
    public function getString(string $key, $defaultValue);

    /**
     * @param string $key
     * @param int|mixed  $defaultValue
     * @return mixed
     */
    public function getInt(string $key, $defaultValue);

    /**
     * @param string $key
     * @param float|mixed  $defaultValue
     * @return mixed
     */
    public function getFloat(string $key, $defaultValue);

    /**
     * @param string $key
     * @param bool|mixed  $defaultValue
     * @return mixed
     */
    public function getBool(string $key, $defaultValue);
}
