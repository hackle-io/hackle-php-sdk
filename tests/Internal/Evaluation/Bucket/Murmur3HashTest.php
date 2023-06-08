<?php

namespace Hackle\Tests\Internal\Evaluation\Bucket;

use Hackle\Internal\Evaluation\Bucket\Murmur3Hash;
use PHPUnit\Framework\TestCase;

class Murmur3HashTest extends TestCase
{
    public function testHash()
    {
        $this->verify("murmur_all");
        $this->verify("murmur_alphabetic");
        $this->verify("murmur_alphanumeric");
        $this->verify("murmur_numeric");
        $this->verify("murmur_uuid");
    }

    private function verify(string $fileName)
    {
        $file = fopen(__DIR__ . "/../../../Resources/$fileName.csv", "r");
        $murmur3Hash = new Murmur3Hash();
        while (($line = fgets($file)) !== false) {
            $row = explode(",", rtrim($line));
            $key = $row[0];
            $seed = intval($row[1]);
            $hashValue = $murmur3Hash->hash($key, $seed);
            $this->assertEquals($row[2], $hashValue);
        }
    }
}
