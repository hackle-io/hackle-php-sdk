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
    private $_coreVersion;

    /** @var MetadataVersion */
    private $_prerelease;

    /** @var MetadataVersion */
    private $_build;

    public function __construct(CoreVersion $_coreVersion, MetadataVersion $_prerelease, MetadataVersion $_build)
    {
        $this->_coreVersion = $_coreVersion;
        $this->_prerelease = $_prerelease;
        $this->_build = $_build;
    }

    public function compareTo(Comparable $other): int
    {
        $result = $this->_coreVersion->compareTo($other->_coreVersion);
        return $result != 0 ? $result : $this->_prerelease->compareTo($other->_prerelease);
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
}
