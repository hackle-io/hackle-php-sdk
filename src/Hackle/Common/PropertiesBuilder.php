<?php

namespace Hackle\Common;

class PropertiesBuilder
{
    private $_properties = [];
    private const MAX_PROPERTIES_COUNT = 128;
    private const MAX_PROPERTY_KEY_LENGTH = 128;
    private const MAX_PROPERTY_VALUE_LENGTH = 1024;

    public function addAll(array $properties): PropertiesBuilder
    {
        foreach ($properties as $key => $value) {
            $this->add($key, $value);
        }
        return $this;
    }

    /**
     * @param mixed $value
     */
    public function add(string $key, $value): PropertiesBuilder
    {
        if (count($this->_properties) >= self::MAX_PROPERTIES_COUNT) {
            return $this;
        }

        if (strlen($key) > self::MAX_PROPERTY_KEY_LENGTH) {
            return $this;
        }

        $sanitizedValue = $this->sanitize($value);

        if (empty($sanitizedValue)) {
            return $this;
        }

        $this->_properties[$key] = $sanitizedValue;
        return $this;
    }

    /**
     * @param mixed $value
     */
    private function sanitize($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_array($value)) {
            $filteredNotNullArr = array_filter($value, function ($val) {
                return !empty($val);
            });
            return array_filter($filteredNotNullArr, function ($element) {
                return $this->isValidElement($element);
            });
        }

        if ($this->isValidValue($value)) {
            return $value;
        }

        return null;
    }

    /**
     * @param mixed $value
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
     */
    private function isValidElement($element): bool
    {
        if (is_string($element)) {
            return strlen($element) <= self::MAX_PROPERTY_VALUE_LENGTH;
        }

        return is_numeric($element);
    }

    public function build(): array
    {
        return $this->_properties;
    }
}
