<?php

namespace Hackle\Tests\Internal\Model;

use Hackle\Internal\Model\Container;
use Hackle\Internal\Model\ContainerGroup;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testContainer()
    {
        self::assertNull((new Container(42, 320, array()))->getGroupOrNull(1));
        $container = new Container(42, 320, array(new ContainerGroup(99, array())));
        self::assertNull($container->getGroupOrNull(100));
        self::assertNotNull($container->getGroupOrNull(99));
        self::assertEquals(42, $container->getId());
        self::assertEquals(320, $container->getBucketId());
        self::assertCount(1, $container->getGroups());
    }
}
