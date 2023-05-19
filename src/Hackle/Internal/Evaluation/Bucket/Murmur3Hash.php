<?php

namespace Hackle\Internal\Evaluation\Bucket;

final class Murmur3Hash
{
    public function hash(string $data, int $seed): int
    {
        $key = $data;
        $klen = strlen($key);
        if (PHP_INT_SIZE > 4) {
            $h1 = $seed & 0xFFFFFFFF; // Since we're using murmur32, make sure that negative seeds are interpreted
            // as a 32 bits 2's compliment unsigned integer, so that the value will be the
            // same in 32 and 64 bits architectures
        } else {
            $h1 = $seed;
        }
        for ($i = 0, $bytes = $klen - ($remainder = $klen & 3); $i < $bytes;) {
            $k1 = ((ord($key[$i]) & 0xff))
                | ((ord($key[++$i]) & 0xff) << 8)
                | ((ord($key[++$i]) & 0xff) << 16)
                | ((ord($key[++$i]) & 0xff) << 24);
            ++$i;

            $k1 = (
                    (($k1 & 0xffff) * 0xcc9e2d51) +
                    ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0xcc9e2d51) & 0xffff) << 16)
                ) & 0xFFFFFFFF;

            $k1 = ($k1 << 15 | ($k1 >= 0 ? $k1 >> 17 : (($k1 & 0x7fffffff) >> 17) | 0x4000)) & 0xffffffff;

            $k1 = (
                    (($k1 & 0xffff) * 0x1b873593) +
                    ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0x1b873593) & 0xffff) << 16)
                ) & 0xFFFFFFFF;
            $h1 ^= $k1;

            $h1 = ($h1 << 13 | ($h1 >= 0 ? $h1 >> 19 : (($h1 & 0x7fffffff) >> 19) | 0x1000)) & 0xFFFFFFFF;

            $h1b = (
                    (($h1 & 0xffff) * 5) +
                    ((((($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000)) * 5) & 0xffff) << 16)
                ) & 0xFFFFFFFF;

            $h1 = (
                    (($h1b & 0xffff) + 0x6b64) +
                    ((((($h1b >= 0 ? $h1b >> 16 : (($h1b & 0x7fffffff) >> 16) | 0x8000)) + 0xe654) & 0xffff) << 16)
                ) & 0xFFFFFFFF;
        }

        $k1 = 0;
        switch ($remainder) {
            case 3:
                $k1 ^= ((ord($key[$i + 2]) & 0xff) << 16) & 0xFFFFFFF;
            // continue
            case 2:
                $k1 ^= ((ord($key[$i + 1]) & 0xff) << 8) & 0XFFFFFFFF;
            // continue
            case 1:
                $k1 ^= (ord($key[$i]) & 0xff);
                $k1 = (
                        (($k1 & 0xffff) * 0xcc9e2d51) +
                        ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0xcc9e2d51) & 0xffff) << 16)
                    ) & 0xFFFFFFFF;
                $k1 = ($k1 << 15 | ($k1 >= 0 ? $k1 >> 17 : (($k1 & 0x7fffffff) >> 17) | 0x4000)) & 0xffffffff;
                $k1 = (
                        (($k1 & 0xffff) * 0x1b873593) +
                        ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0x1b873593) & 0xffff) << 16)
                    ) & 0xFFFFFFFF;
                $h1 ^= $k1;
        }
        $h1 ^= $klen;
        $h1 ^= ($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000);

        $h1 = (
                (($h1 & 0xffff) * 0x85ebca6b) +
                ((((($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000)) * 0x85ebca6b) & 0xffff) << 16)
            ) & 0xFFFFFFFF;

        $h1 ^= ($h1 >= 0 ? $h1 >> 13 : (($h1 & 0x7fffffff) >> 13) | 0x40000);

        $h1 = (
            ((($h1 & 0xffff) * 0xc2b2ae35) +
                ((((($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000)) * 0xc2b2ae35) & 0xffff) << 16))
            ) & 0xFFFFFFFF;
        $h1 ^= ($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000);
        return $h1;
    }
}