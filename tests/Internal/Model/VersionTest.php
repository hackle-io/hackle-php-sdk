<?php

namespace Hackle\Tests\Internal\Model;

use Hackle\Internal\Model\CoreVersion;
use Hackle\Internal\Model\MetadataVersion;
use Hackle\Internal\Model\Version;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function testAlreadyVersionTypeReturnItAsIt()
    {
        $version = $this->createMock(Version::class);
        $actual = Version::parseOrNull($version);
        self::assertInstanceOf(Version::class, $actual);
    }

    public function testReturnNullIfNotVersionOrStringType()
    {
        $actual = Version::parseOrNull(1.2);
        self::assertNull($actual);
    }

    public function testInvalidFormat()
    {
        $this->verifyNull("01.0.0");
        $this->verifyNull("1.01.0");
        $this->verifyNull("1.1.01");
        $this->verifyNull("2.x");
        $this->verifyNull("2.3.x");
        $this->verifyNull("2.3.1.4");
        $this->verifyNull("2.3.1*beta");
        $this->verifyNull("2.3.1-beta*");
        $this->verifyNull("2.3.1-beta_4");
    }

    public function testSemanticCoreVersionParse()
    {
        $this->verify("1.0.0", 1, 0, 0);
        $this->verify("14.165.14", 14, 165, 14);
    }

    public function testSemanticVersionWithPrerelease()
    {
        $this->verify("1.0.0-beta1", 1, 0, 0, array("beta1"));
        $this->verify("1.0.0-beta.1", 1, 0, 0, array("beta", "1"));
        $this->verify("1.0.0-x.y.z", 1, 0, 0, array("x", "y", "z"));
    }

    public function testSemanticVersionWithBuild()
    {
        $this->verify("1.0.0+build1", 1, 0, 0, array(), array("build1"));
        $this->verify("1.0.0+build.1", 1, 0, 0, array(), array("build", "1"));
        $this->verify("1.0.0+1.2.3", 1, 0, 0, array(), array("1", "2", "3"));
    }

    public function testSemanticVersionWithPrereleaseAndBuild()
    {
        $this->verify("1.0.0-alpha.3.rc.5+build.53", 1, 0, 0, array("alpha", "3", "rc", "5"), array("build", "53"));
    }

    public function testFillZeroIfMinorOrPatchNotExist()
    {
        $this->verify("15", 15, 0, 0);
        $this->verify("15.143", 15, 143, 0);
        $this->verify("15-x.y.z", 15, 0, 0, array("x", "y", "z"));
        $this->verify("15-x.y.z+a.b.c", 15, 0, 0, array("x", "y", "z"), array("a", "b", "c"));
    }

    public function testSameVersionIfOnlyCoreVersionExist()
    {
        $version = $this->v("2.3.4");
        self::assertTrue($version->compareTo($version) === 0);
        self::assertTrue($this->v("2.3.4")->compareTo($this->v("2.3.4")) === 0);
    }

    public function testSameVersionIfCoreAndPrereleaseAllSame()
    {
        self::assertTrue($this->v("2.3.4-beta.1")->compareTo($this->v("2.3.4-beta.1")) === 0);
    }

    public function testMissMatchIfPrereleaseDiff()
    {
        self::assertTrue($this->v("2.3.4-beta.1")->compareTo($this->v("2.3.4-beta.2")) !== 0);
    }

    public function testMissMatchIfBuildDiff()
    {
        self::assertTrue($this->v("2.3.4+build.111")->compareTo($this->v("2.3.4+build.222")) === 0);
        self::assertTrue($this->v("2.3.4-beta.1+build.111")->compareTo($this->v("2.3.4-beta.1+build.222")) === 0);
    }

    public function testCompareFirstMajor()
    {
        self::assertTrue($this->v("4.5.7")->compareTo($this->v("3.5.7")) > 0);
        self::assertTrue($this->v("2.5.7")->compareTo($this->v("3.5.7")) < 0);
    }

    public function testCompareMinorIfMajorSame()
    {
        self::assertTrue($this->v("3.6.7")->compareTo($this->v("3.5.7")) > 0);
        self::assertTrue($this->v("3.4.7")->compareTo($this->v("3.5.7")) < 0);
    }

    public function testComparePatchIfMajorAndMinorSame()
    {
        self::assertTrue($this->v("3.5.8")->compareTo($this->v("3.5.7")) > 0);
        self::assertTrue($this->v("3.5.6")->compareTo($this->v("3.5.7")) < 0);
    }

    public function testOfficialVersionIsHigherVersion()
    {
        self::assertTrue($this->v("3.5.7")->compareTo($this->v("3.5.7-beta")) > 0);
        self::assertTrue($this->v("3.5.7-alpha")->compareTo($this->v("3.5.7")) < 0);
    }

    public function testComparedByMagnitudeIfPrereleaseConsistOfOnlyDigit()
    {
        self::assertTrue($this->v("3.5.7-1")->compareTo($this->v("3.5.7-2")) < 0);
        self::assertTrue($this->v("3.5.7-1.1")->compareTo($this->v("3.5.7-1.2")) < 0);
        self::assertTrue($this->v("3.5.7-11")->compareTo($this->v("3.5.7-1")) >0);
    }

    public function testSortByAsciiIfPrereleaseIncludedAlphabet()
    {
        self::assertTrue($this->v("3.5.7-a")->compareTo($this->v("3.5.7-a")) == 0);
        self::assertTrue($this->v("3.5.7-a")->compareTo($this->v("3.5.7-b")) < 0);
        self::assertTrue($this->v("3.5.7-az")->compareTo($this->v("3.5.7-ab")) > 0);
    }

    public function testLowerPriorityThanIdentifierWithLetterAndHyphens()
    {
        self::assertTrue($this->v("3.5.7-9")->compareTo($this->v("3.5.7-a")) < 0);
        self::assertTrue($this->v("3.5.7-9")->compareTo($this->v("3.5.7-a-9")) < 0);
        self::assertTrue($this->v("3.5.7-beta")->compareTo($this->v("3.5.7-1")) > 0);
    }

    public function testMoreFieldHaveSideHigherPriorityIfPrereleaseSame()
    {
        self::assertTrue($this->v("1.0.0-alpha")->compareTo($this->v("1.0.0-alpha.1")) < 0);
        self::assertTrue($this->v("1.0.0-1.2.3")->compareTo($this->v("1.0.0-1.2.3.4")) < 0);
    }

    public function testToStringTest()
    {
        self::assertEquals("Version(1.0.0)", $this->v("1.0.0")."");
        self::assertEquals("Version(1.0.0-beta)", $this->v("1.0.0-beta")."");
        self::assertEquals("Version(1.0.0-beta+build)", $this->v("1.0.0-beta+build")."");
        self::assertEquals("Version(1.0.0+build)", $this->v("1.0.0+build")."");
    }
    private function verifyNull(string $version)
    {
        self::assertNull(Version::parseOrNull($version));
    }

    private function verify(
        string $version,
        int $major,
        int $minor,
        int $patch,
        array $prerelease = [],
        array $build = []
    ) {
        $actual = Version::parseOrNull($version);
        self::assertNotNull($actual);
        self::assertEquals(new CoreVersion($major, $minor, $patch), $actual->getCoreVersion());
        self::assertEquals(new MetadataVersion($prerelease), $actual->getPrerelease());
        self::assertEquals(new MetadataVersion($build), $actual->getBuild());
    }

    private function v(string $value): Version
    {
        return Version::parseOrNull($value);
    }
}
