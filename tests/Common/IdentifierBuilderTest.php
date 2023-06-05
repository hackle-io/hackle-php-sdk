<?php

namespace Hackle\Tests\Common;

use Hackle\Common\IdentifiersBuilder;
use PHPUnit\Framework\TestCase;

class IdentifierBuilderTest extends TestCase
{
    public function testIdentifierTypeLength128()
    {
        self::assertEmpty((new IdentifiersBuilder())->add(str_repeat("a", 129), "test")->build());
    }

    public function testIdentifierValueLength512()
    {
        self::assertEmpty((new IdentifiersBuilder())->add("a", str_repeat("a", 513))->build());
    }

    public function testIdentifierValueNotNull()
    {
        self::assertEmpty((new IdentifiersBuilder())->add("a", null)->build());
    }

    public function testIdentifierValueNotBlank()
    {
        self::assertEmpty((new IdentifiersBuilder())->add("a", " ")->build());
    }

    public function testIdentifierValueNotEmpty()
    {
        self::assertEmpty((new IdentifiersBuilder())->add("a", "")->build());
    }

    public function testOverwrite()
    {
        self::assertEquals("value2", (new IdentifiersBuilder())
            ->add("key", "value")
            ->add("key", "value2")->build()["key"]);
        self::assertEquals("value", (new IdentifiersBuilder())
            ->add("key", "value")
            ->add("key", "value2", false)
            ->build()["key"]);
        self::assertEquals("value2", (new IdentifiersBuilder())
            ->add("key", "value2", false)
            ->build()["key"]);
    }

    public function testBuild()
    {
        $identifiers = new IdentifiersBuilder();
        $identifiers->add(str_repeat("a", 128), str_repeat("a", 512));
        $identifiers->addAll(array("a" => "a"));

        self::assertCount(2, $identifiers->build());
        self::assertEquals(
            array(
                str_repeat("a", 128) => str_repeat("a", 512),
                "a" => "a"
            ),
            $identifiers->build()
        );
    }
}
