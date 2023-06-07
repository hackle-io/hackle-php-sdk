<?php

namespace Hackle\Common;

class PropertiesBuilder
{
    private const MAX_PROPERTIES_COUNT = 128;
    private const MAX_PROPERTY_KEY_LENGTH = 128;
    private const MAX_PROPERTY_VALUE_LENGTH = 1024;

    /**
     * @var array<string, mixed>
     */
    private $properties = [];

    /**
     * @param array<string, mixed> $properties
     * @return PropertiesBuilder
     */
    public function addAll(array $properties): self
    {
        foreach ($properties as $key => $value) {
            $this->add($key, $value);
        }
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return PropertiesBuilder
     */
    public function add(string $key, $value): self
    {
        if (count($this->properties) >= self::MAX_PROPERTIES_COUNT) {
            return $this;
        }

        if (strlen($key) > self::MAX_PROPERTY_KEY_LENGTH) {
            return $this;
        }

        $sanitizedValue = $this->sanitize($value);

        if (is_null($sanitizedValue)) {
            return $this;
        }

        $this->properties[$key] = $sanitizedValue;
        return $this;
    }

    /**
     * @param mixed $value
     * @return mixed|null
     */
    private function sanitize($value)
    {
        if (is_null($value)) {
            return null;
        }

        if (is_array($value)) {
            return array_values(array_filter($value, function ($val) {
                return !is_null($val) && $this->isValidElement($val);
            }));
        }

        if ($this->isValidValue($value)) {
            return $value;
        }

        return null;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function isValidValue($value): bool
    {
        if (is_string($value)) {
            return strlen($value) <= self::MAX_PROPERTY_VALUE_LENGTH;
        }

        return is_bool($value) || is_numeric($value);
    }

    /**
     * @param mixed $element
     * @return bool
     */
    private function isValidElement($element): bool
    {
        if (is_string($element)) {
            return strlen($element) <= self::MAX_PROPERTY_VALUE_LENGTH;
        }

        return is_numeric($element);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return $this->properties;
    }
}
