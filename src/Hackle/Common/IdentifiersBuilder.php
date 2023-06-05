<?php

namespace Hackle\Common;

class IdentifiersBuilder
{
    private const  MAX_IDENTIFIER_TYPE_LENGTH = 128;
    private const  MAX_IDENTIFIER_VALUE_LENGTH = 512;

    /**
     * @var array<string, string>
     */
    private $identifiers = [];

    /**
     * @param array<string, ?string> $identifiers
     * @param bool $overwrite
     * @return IdentifiersBuilder
     */
    public function addAll(array $identifiers, bool $overwrite = true): self
    {
        foreach ($identifiers as $type => $value) {
            $this->add($type, $value, $overwrite);
        }
        return $this;
    }

    public function add(string $type, ?string $value, bool $overwrite = true): self
    {
        if (!$overwrite && array_key_exists($type, $this->identifiers)) {
            return $this;
        }

        if (!is_null($value) && $this->isValid($type, $value)) {
            $this->identifiers[$type] = $value;
        }

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function build(): array
    {
        return $this->identifiers;
    }

    private function isValid(string $type, ?string $value): bool
    {
        if (strlen($type) > self::MAX_IDENTIFIER_TYPE_LENGTH) {
            return false;
        }

        if (strlen($value) > self::MAX_IDENTIFIER_VALUE_LENGTH) {
            return false;
        }

        if (strlen(trim($value)) === 0) {
            return false;
        }

        return true;
    }
}
