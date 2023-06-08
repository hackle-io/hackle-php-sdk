<?php

namespace Hackle\Internal\Model;

class MetadataVersion
{
    /** @var array */
    private $identifiers;

    public function __construct(array $identifiers)
    {
        $this->identifiers = $identifiers;
    }

    public function isEmpty(): bool
    {
        return empty($this->identifiers);
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public static function getEmpty(): MetadataVersion
    {
        return new MetadataVersion(array());
    }

    public static function parse(?string $value): MetadataVersion
    {
        if (empty($value)) {
            return self::getEmpty();
        } else {
            return new MetadataVersion(explode(".", $value));
        }
    }

    public function compareTo(MetadataVersion $other): int
    {
        if ($this->isEmpty() && $other->isEmpty()) {
            return 0;
        }
        if ($this->isEmpty() && $other->isNotEmpty()) {
            return 1;
        }
        if ($this->isNotEmpty() && $other->isEmpty()) {
            return -1;
        }
        return $this->compareIdentifiers($other);
    }

    public function compareIdentifiers(MetadataVersion $other): int
    {
        $size = min(Count($this->identifiers), Count($other->identifiers));
        for ($i = 0; $i < $size; $i++) {
            $result = $this->compareIdentifier($this->identifiers[$i], $other->identifiers[$i]);
            if ($result != 0) {
                return $result;
            }
        }
        return $this->intCompareTo(Count($this->identifiers), Count($other->identifiers));
    }

    private function compareIdentifier(string $identifier1, string $identifier2): int
    {
        $num1 = is_numeric($identifier1) ? intval($identifier1) : null;
        $num2 = is_numeric($identifier2) ? intval($identifier2) : null;
        if ($num1 != null && $num2 != null) {
            return $this->intCompareTo($num1, $num2);
        } else {
            return strcmp($identifier1, $identifier2);
        }
    }

    private function intCompareTo(int $num1, int $num2): int
    {
        if ($num1 == $num2) {
            return 0;
        } elseif ($num1 < $num2) {
            return -1;
        } else {
            return 1;
        }
    }

    public function __toString(): string
    {
        return join(".", $this->identifiers);
    }
}
