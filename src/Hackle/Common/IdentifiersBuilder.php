<?php

namespace Hackle\Common;

class IdentifiersBuilder
{
    private const  MAX_IDENTIFIER_TYPE_LENGTH = 128;
    private const  MAX_IDENTIFIER_VALUE_LENGTH = 512;
    private $identifiers = [];

    public function addAll(array $identifiers, bool $overwrite = true): IdentifiersBuilder
    {
        foreach ($identifiers as $type => $value) {
            $this->add($type, $value, $overwrite);
        }
        return $this;
    }

    public function add(string $type, ?string $value, bool $overwrite = true): IdentifiersBuilder
    {
        if (!$overwrite && array_key_exists($type, $this->identifiers)) {
            return $this;
        }

        if ($this->isValid($type, $value)) {
            $this->identifiers[$type] = $value;
        }

        return $this;
    }

    public function build(): array
    {
        return $this->identifiers;
    }

    private function isValid(string $type, string $value): bool
    {
        if (strlen($type) > self::MAX_IDENTIFIER_TYPE_LENGTH) {
            return false;
        }

        if (strlen($value) > self::MAX_IDENTIFIER_VALUE_LENGTH) {
            return false;
        }

        if (empty(trim($value))) {
            return false;
        }

        return true;
    }
}
