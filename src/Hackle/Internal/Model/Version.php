<?php

namespace Hackle\Internal\Model;

use Hackle\Internal\Lang\Comparable;

/**
 * @implements Comparable<Version>
 */
class Version implements Comparable
{
    private const PATTERN = '/^(?<major>0|[1-9]\d*)(\.(?<minor>0|[1-9]\d*))?(\.(?<patch>0|[1-9]\d*))?(\-(?<prerelease>[0-9A-Za-z\-\.]+))?(\+(?<build>[0-9A-Za-z\-\.]+))?$/';

    /** @var CoreVersion */
    private $coreVersion;

    /** @var MetadataVersion */
    private $prerelease;

    /** @var MetadataVersion */
    private $build;

    /**
     * @param CoreVersion $coreVersion
     * @param MetadataVersion $prerelease
     * @param MetadataVersion $build
     */
    public function __construct(CoreVersion $coreVersion, MetadataVersion $prerelease, MetadataVersion $build)
    {
        $this->coreVersion = $coreVersion;
        $this->prerelease = $prerelease;
        $this->build = $build;
    }

    public function compareTo(Comparable $other): int
    {
        $result = $this->coreVersion->compareTo($other->coreVersion);
        return $result != 0 ? $result : $this->prerelease->compareTo($other->prerelease);
    }

    private static function parse(string $version): ?Version
    {
        preg_match(self::PATTERN, $version, $matches);
        if (empty($matches)) {
            return null;
        }
        $major = intval($matches['major']);
        $minor = array_key_exists('minor', $matches) ? intval($matches['minor']) : 0;
        $patch = array_key_exists('patch', $matches) ? intval($matches['patch']) : 0;
        $coreVersion = new CoreVersion($major, $minor, $patch);
        $prerelease = array_key_exists('prerelease', $matches) ? MetadataVersion::parse($matches['prerelease']) : MetadataVersion::getEmpty();
        $build = array_key_exists('build', $matches) ? MetadataVersion::parse($matches['build']) : MetadataVersion::getEmpty();
        return new Version($coreVersion, $prerelease, $build);
    }

    public static function parseOrNull($value): ?Version
    {
        if ($value instanceof Version) {
            return $value;
        }
        if (!is_string($value)) {
            return null;
        }
        return self::parse($value);
    }

    public function getPlainString(): string
    {
        $plainString = $this->coreVersion;
        if ($this->prerelease->isNotEmpty()) {
            $plainString .= "-" . $this->prerelease;
        }
        if ($this->build->isNotEmpty()) {
            $plainString .= "+" . $this->build;
        }
        return $plainString;
    }

    /**
     * @return CoreVersion
     */
    public function getCoreVersion(): CoreVersion
    {
        return $this->coreVersion;
    }

    /**
     * @return MetadataVersion
     */
    public function getPrerelease(): MetadataVersion
    {
        return $this->prerelease;
    }

    /**
     * @return MetadataVersion
     */
    public function getBuild(): MetadataVersion
    {
        return $this->build;
    }
    public function __toString(): string
    {
        return "Version(".$this->getPlainString().")";
    }
}
