<?php

namespace Hackle\Internal\Repository;

class FileRepository implements Repository
{
    /**
     * @var string
     */
    private $dir;

    /**
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->dir = self::ensureTrailingSlash($directory);
    }

    public function get(string $key): ?string
    {
        $path = $this->resolvePath($key);
        $contents = @file_get_contents($path);
        if ($contents === false) {
            return null;
        }
        return $contents;
    }

    public function set(string $key, ?string $value)
    {
        $path = $this->resolvePath($key);
        if ($value === null) {
            unlink($path);
            return;
        }
        $this->write($path, $value);
    }

    private function write(string $path, string $contents)
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        file_put_contents($path, $contents);
    }

    private function resolvePath(string $key): string
    {
        return $this->dir . base64_encode($key);
    }

    private static function ensureTrailingSlash(string $path): string
    {
        if (substr($path, -1) !== '/') {
            $path .= '/';
        }
        return $path;
    }
}
